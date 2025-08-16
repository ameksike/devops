
## Queryable Encryption

**Queryable Encryption** in MongoDB provides the capability to query encrypted fields without decrypting them first, supporting encryption during processing. Unlike CSFLE, this method does not require choosing an encryption algorithm (it automatically ensures queryability). This means you can perform normal queries on encrypted data without server-side access to plaintext, enhancing security.

### Secure Data Masking

In contrast, to share data with third parties without exposing sensitive information, irreversible data masking ensures security by obfuscating sensitive fields without the need for encryption. The provided example outlines several techniques to modify sensitive payment information, making the data unreadable by unauthorized entities while maintaining data utility for analysis.

### Queryable Encryption in MongoDB

Queryable Encryption allows you to encrypt specific fields in a document and still perform equality queries on those encrypted fields without having to decrypt the data first on the server side. This is achieved using a new set of features and APIs, predominantly available in newer MongoDB versions.

#### Prerequisites:
- You need MongoDB version that supports Queryable Encryption (6.0 or newer).
- The MongoDB driver for Node.js, which supports Queryable Encryption features.

### Example: Setting Up and Using Queryable Encryption with Node.js

1. **Installation**

   First, ensure you have the latest MongoDB Node.js driver that supports Queryable Encryption:

   ```bash
   npm install mongodb
   npm install @mongodb/js-bson
   ```

2. **KMS Setup and Key Management**

   **Using KMS (AWS KMS Example):**

   ```javascript
   const { MongoClient, AutoEncryption, ClientEncryption } = require('mongodb');
   const AWS = require('aws-sdk');

   AWS.config.update({
     accessKeyId: process.env.AWS_ACCESS_KEY_ID,
     secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
     region: 'us-east-1'
   });

   (async () => {
     const client = new MongoClient('mongodb+srv://<username>:<password>@cluster.mongodb.net/test', {
       useNewUrlParser: true,
       useUnifiedTopology: true,
       autoEncryption: {
         keyVaultNamespace: 'encryption.__keyVault',
         kmsProviders: {
           aws: {
             accessKeyId: process.env.AWS_ACCESS_KEY_ID,
             secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
           },
         },
         keyVaultClient: new MongoClient('mongodb://localhost:27017')
       }
     });

     try {
       await client.connect();

       const db = client.db('test');
       const collection = db.collection('encryptedData');

       // Insert Data
       const encryptedInsert = await collection.insertOne({
         ssn: '123-456-7890',  // This field needs to be encrypted
       });

       console.log('Inserted document:', encryptedInsert.insertedId);

       // Query Encrypted Data
       const encryptedQuery = await collection.findOne({ ssn: '123-456-7890' });
       console.log('Queried document:', encryptedQuery);

     } finally {
       await client.close();
     }
   })().catch(console.dir);
   ```

3. **Local Key Management Example:**

   When using local key management, you replace KMS setup with a locally managed key:

   ```javascript
   const fs = require('fs');
   const { MongoClient } = require('mongodb');

   const localMasterKey = fs.readFileSync('local-keyfile');

   (async () => {
     const client = new MongoClient('mongodb://localhost:27017', {
       useNewUrlParser: true,
       useUnifiedTopology: true,
       autoEncryption: {
         keyVaultNamespace: 'encryption.__keyVault',
         kmsProviders: {
           local: {
             key: localMasterKey,
           },
         },
       },
     });

     try {
       await client.connect();

       const db = client.db('test');
       const collection = db.collection('encryptedData');

       const encryptedInsert = await collection.insertOne({
         ssn: '123-456-7890',  // Field to be encrypted
       });

       console.log('Inserted document:', encryptedInsert.insertedId);

       const encryptedQuery = await collection.findOne({ ssn: '123-456-7890' });
       console.log('Queried document:', encryptedQuery);

     } finally {
       await client.close();
     }
   })().catch(console.error);
   ```

### Conclusion

While CSFLE and Queryable Encryption ensure data remains encrypted during processing, each serves different roles:
- **CSFLE** provides strong encryption with selective queryability via deterministic encryption.
- **Queryable Encryption** facilitates querying without decrypting fields upfront, built to handle encrypted queries transparently.

Implementing such in-use encryption policies ensures data remains secure and protected against unauthorized access, while still providing necessary usage functions like querying sensitive content efficiently.  

### Key Considerations:
- **Setup Requirements**: Ensure accurate configuration of the encryption key management (whether using a KMS or a local key).
- **Driver Version**: Use the latest MongoDB driver supporting these features.
- **Encryption Algorithms**: MongoDB handles encryption internally, ensuring consistency when querying encrypted data.
- **Security Best Practices**: Always ensure keys and environment variables are securely managed.

By implementing Queryable Encryption, MongoDB empowers developers to execute highly secure functions by allowing processing of encrypted fields without needing prior decryption, enhancing both security and efficiency in data handling operations.  

**References**  
  
[Secure Your Data](https://mongodb.com/docs/languages/kotlin/kotlin-sync-driver/current/security/)  
[Mask Sensitive Fields](https://www.practical-mongodb-aggregations.com/examples/securing-data/mask-sensitive-fields)  
[In-Use Encryption](https://mongodb.com/docs/languages/kotlin/kotlin-sync-driver/current/security/encrypt-fields/)  
[In-Use Encryption](https://mongodb.com/docs/drivers/kotlin/coroutine/current/fundamentals/encrypt-fields/)  
[Live Migrate (Pull) a Replica Set into Atlas (MongoDB Before 6.0.17)](https://mongodb.com/docs/atlas/import/live-import/)  
[Live Migrate (Pull) a MongoDB 6.0.17+ or 7.0.13+ Cluster into Atlas](https://mongodb.com/docs/atlas/import/c2c-pull-live-migration/)  
[Methods](https://mongodb.com/docs/mongodb-shell/reference/methods/)  
[In-Use Encryption Tutorial](https://mongodb.com/docs/compass/current/in-use-encryption-tutorial/)
[Set Validation Rules for Your Schema](https://mongodb.com/docs/compass/current/validation/) 
[SOLUTIONS](https://www.mongodb.com/solutions/solutions-library/hasura-ddn-fintech)  
[Archive Pattern](https://mongodb.com/docs/manual/data-modeling/design-patterns/archive/)  
[Vector Quantization](https://mongodb.com/docs/atlas/atlas-vector-search/vector-quantization/)