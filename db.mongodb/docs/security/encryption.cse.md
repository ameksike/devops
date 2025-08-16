## General Client-Side Encryption (CSE)

General Client-Side Encryption refers to encrypting data within your application before it's stored in any persistence layer, including MongoDB. Unlike CSFLE, which specifically targets field-level encryption in MongoDB documents, General CSE can utilize any encryption library or technique suited to your application requirements.

Below is an example of implementing General Client-Side Encryption using Node.js. I’ll demonstrate using the `crypto` module included in Node.js for simplicity.

#### Example: General Client-Side Encryption with Node.js

1. **Install MongoDB Driver**

   Make sure to install the MongoDB driver if it's not already present:

   ```bash
   npm install mongodb
   ```

2. **Sample Code Using Node.js Built-In `crypto` Library**

   This example will encrypt a message before storing it in MongoDB and decrypt it after retrieving it from the database.

   ```javascript
   const { MongoClient } = require('mongodb');
   const crypto = require('crypto');

   // Encryption utility functions
   function encrypt(text, secret) {
     const cipher = crypto.createCipheriv('aes-256-cbc', secret, Buffer.alloc(16, 0));
     let encrypted = cipher.update(text, 'utf8', 'hex');
     encrypted += cipher.final('hex');
     return encrypted;
   }

   function decrypt(encryptedText, secret) {
     const decipher = crypto.createDecipheriv('aes-256-cbc', secret, Buffer.alloc(16, 0));
     let decrypted = decipher.update(encryptedText, 'hex', 'utf8');
     decrypted += decipher.final('utf8');
     return decrypted;
   }

   const secretKey = crypto.randomBytes(32); // 256-bit key

   async function runGeneralCSE() {
     const client = new MongoClient('mongodb://localhost:27017', { useUnifiedTopology: true });
     try {
       await client.connect();
       const db = client.db('mydatabase');

       // Encrypt and store data
       const message = "Sensitive Information";
       const encryptedMessage = encrypt(message, secretKey);
       await db.collection('clientEncryptedData').insertOne({ encryptedField: encryptedMessage });

       // Retrieve and decrypt data
       const doc = await db.collection('clientEncryptedData').findOne();
       const decryptedMessage = decrypt(doc.encryptedField, secretKey);
       console.log('Decrypted Message:', decryptedMessage);
       
     } finally {
       await client.close();
     }
   }

   runGeneralCSE().catch(console.error);
   ```

### ODMs for MongoDB 

When dealing with databases, applications often use some kind of mapping between stored data and their representations in code (i.e., objects). For NoSQL databases like MongoDB, this mapping can be managed through an ODM (Object-Document Mapper). One of the most popular ODMs for MongoDB and Node.js is Mongoose.

#### Advantages of Using an ODM:

1. **Schema Modeling:**
   - Mongoose allows you to define schemas for your collections, thus applying certain validations and structures to your data. This can be useful for maintaining data consistency, especially in large teams.
   - Example: Defining a schema with Mongoose:
     ```js
     const mongoose = require('mongoose');

     const userSchema = new mongoose.Schema({
       name: String,
       email: { type: String, required: true, unique: true },
       password: String,
     });

     const User = mongoose.model('User', userSchema);
     ```

2. **Data Validation:**
   - Incorporating validations directly in the schema (like required, unique, format, etc.) can reduce errors and clean up application-level logic.

3. **Middleware:**
   - Mongoose allows you to use middleware (hooks), which execute at different stages of a document’s lifecycle, such as before saving or deleting. This can automatically handle actions like password hashing.
   - Example: Using middleware for password hashing:
     ```js
     userSchema.pre('save', async function (next) {
       if (!this.isModified('password')) return next();
       this.password = await hashPassword(this.password);
       next();
     });
     ```

4. **Custom Methods:**
   - You can add methods to schemas, providing a way to encapsulate domain logic related to the document.

5. **Simplified Queries:**
   - Mongoose offers a more expressive and framework-agnostic query system, simplifying the code required to perform certain operations.

#### Disadvantages of Using an ODM:

1. **Added Complexity:**
   - It adds an extra layer of abstraction that requires learning and might be unnecessary for simple use cases.

2. **Performance:**
   - Abstraction can add some overhead, and in situations where performance is critical, using the native driver can be more efficient.

3. **Flexibility:**
   - Defining schemas in a database, which is inherently schema-less, can reduce some of the flexibility MongoDB offers.

#### Advantages of MongoDB Native Driver:

1. **Simplicity and Control:**
   - You can fully leverage MongoDB’s flexibility without being constrained by the abstraction layer.

2. **Performance:**
   - Less overhead when using the native driver, which is crucial in high-performance applications.

#### Security Considerations:

Both using an ODM and a native driver require careful security implementations:

- **Authentication and Authorization:** Implement robust strategies for authentication (e.g., JWT, OAuth) and control the access permissions to different resources and operations.
- **Encryption:** Implement encryption for any sensitive information, both in transit (using HTTPS) and at rest (encrypting fields within MongoDB).

### Encryption with Mongoose

Mongoose itself does not natively support encryption during data processing; encryption is typically handled using a separate cryptography library, like `crypto` or `bcrypt` in Node.js. Here is how you can implement encryption with Mongoose:

```js
const mongoose = require('mongoose');
const crypto = require('crypto');

const secretKey = 'supersecretkey'; // Store this securely

const encrypt = (text) => {
  const cipher = crypto.createCipher('aes-256-cbc', secretKey);
  let encrypted = cipher.update(text, 'utf8', 'hex');
  encrypted += cipher.final('hex');
  return encrypted;
};

const decrypt = (encrypted) => {
  const decipher = crypto.createDecipher('aes-256-cbc', secretKey);
  let decrypted = decipher.update(encrypted, 'hex', 'utf8');
  decrypted += decipher.final('utf8');
  return decrypted;
};

const secureDataSchema = new mongoose.Schema({
  sensitiveData: { type: String, required: true },
});

secureDataSchema.pre('save', function (next) {
  this.sensitiveData = encrypt(this.sensitiveData);
  next();
});

secureDataSchema.methods.getSensitiveData = function () {
  return decrypt(this.sensitiveData);
};

const SecureData = mongoose.model('SecureData', secureDataSchema);

module.exports = SecureData;
```

### Comparative View: General CSE vs. CSFLE

| Feature                | General Client-Side Encryption (CSE)    | Client-Side Field Level Encryption (CSFLE) |
|------------------------|-----------------------------------------|--------------------------------------------|
| **Implementation**     | Uses any encryption library/method      | MongoDB-specific API through drivers       |
| **Target**             | Entire data (documents, fields, files)  | Specific fields in MongoDB documents       |
| **Encryption Level**   | Application-controlled, flexible        | Field-level control using MongoDB          |
| **Query Capabilities** | Cannot natively query encrypted data    | Supports deterministic queryable encryption|
| **Setup Complexity**   | Variable, based on approach chosen      | Requires setup with MongoDB ecosystem      |
| **Use Cases**          | General encryption requirements         | Specifically tailored for MongoDB users    |

### Use Cases

- **General CSE**: When you require encryption independent of MongoDB's built-in capabilities and when you need to manage encryption for multiple systems or data stores.
- **CSFLE**: Specifically for applications needing to encrypt sensitive fields within MongoDB and potentially query them securely.

Both methods offer robust security practices but differ in their integration and level of specificity to MongoDB's environment, providing flexibility depending on your data protection requirements. Use case considerations, such as regulatory compliance and the need for querying encrypted data, will guide the choice.  
  


**References**  
- [MongoDB Encryption During Processing](./encryption.csfle.md)
- [MongoDB Node Driver](https://mongodb.com/docs/drivers/node/current/)  
- [Ruby MongoDB Driver](https://mongodb.com/docs/ruby-driver/current/)  
- [Differences Between require() and load()](https://mongodb.com/docs/mongodb-shell/write-scripts/require-load-differences/)  
- [Symfony MongoDB Integration](https://mongodb.com/docs/drivers/php-frameworks/symfony/)  
- [MongoDB database connector](https://www.prisma.io/docs/orm/overview/databases/mongodb)  
- [PHP Libraries, Frameworks, and Tools](https://mongodb.com/docs/drivers/php-libraries/)  
- [Migrating from PostgreSQL to MongoDB](https://www.mongodb.com/resources/compare/mongodb-postgresql/dsl-migrating-postgres-to-mongodb)  
- [Use Snippets in the Console](https://mongodb.com/docs/mongodb-shell/snippets/working-with-snippets/)
- [Client-Side Encryption](https://mongodb.com/docs/ruby-driver/current/reference/in-use-encryption/client-side-encryption/)  
- [Methods](https://mongodb.com/docs/mongodb-shell/reference/methods/)