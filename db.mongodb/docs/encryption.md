The document content outlines various MongoDB validation features and emphasizes the importance of schema validation using different approaches such as JSON Schema or query operators. It also describes the capabilities of MongoDB regarding financial applications and strategies for performance and security. Regarding encryption techniques such as Queryable Encryption, Client-Side Field Level Encryption (CSFLE), Client-Side Encryption, and Encryption at Rest, it's crucial to understand their differences and use cases:

### Encryption Techniques in MongoDB

1. **Queryable Encryption**
    - **Overview**: Allows searching over encrypted fields without decrypting them before query execution. It uses encryption algorithms that support equality queries directly on encrypted data.
    - **Use Case**: Ideal for applications needing to query sensitive data stored in encrypted form, ensuring enhanced security without compromising query performance. Particularly useful in scenarios where querying encrypted data is essential, such as searching within encrypted sensitive datasets.
    - **Implementation**: Typically integrated with MongoDB Atlas but can be managed using the MongoDB native driver in environments that support such features.
    - **Newer Approach with MongoDB 6.0 and later**: Allows certain query operations directly on encrypted data stored in MongoDB, enhancing security without sacrificing querying capabilities.
    - **Automatic Algorithm Use**: Ensures that every encrypted value is uniquely encrypted, minimizing exposure to frequency analysis.

2. **Client-Side Field Level Encryption (CSFLE)**
    - **Overview**: Encrypts specific fields client-side before insertion into MongoDB. It ensures data privacy by not exposing plaintext data to the server.
    - **Use Case**: Suitable for scenarios requiring strict data privacy where encryption keys are managed client-side, ensuring that server administrators cannot access unencrypted data. Ideal for encrypting sensitive data, like social security numbers, before it reaches MongoDB, ensuring even MongoDB admins can't read it without keys.
    - **Implementation**: Uses deterministic or random encryption algorithms. Deterministic allows querying of encrypted data while random offers more security for non-queryable fields.
    - **Specific to MongoDB 4.2 and newer:** Enables field-level encryption, where specific fields are encrypted client-side before transit.
    - **Deterministic/Random Encryption:** CSFLE allows both deterministic (queryable) and random encryption.
    - **Integration with MongoDB Drivers:** Requires setup within MongoDB drivers, supporting specific API calls to encrypt/decrypt fields.

3. **Client-Side Encryption (CSE)**
   - **Overview**: Encrypts data client-side before sending it to the server, typically without integrated query capability.
   - **Use Case**: Used when encryption needs to be in the application layer, providing protection while maintaining data sovereignty and security during transmission.
    - **Broader Concept**: Refers generally to any encryption activity performed on client data before sending it to a server.
    - **Not Specific to MongoDB APIs**: Can be done using various libraries or techniques irrespective of database specifics.
    - **User-Implemented**: Can vary widely in implementation, focusing on encrypting entire documents or fields without server involvement in the encryption/decryption process.


4. **Encryption at Rest**
   - **Overview**: Automatically encrypts data stored on disk (in storage devices). It secures data even when disks or backups are accessed.
   - **Use Case**: Adds a layer of security to data by ensuring that it is protected at the storage level, particularly useful for compliance with regulations requiring data protection.

### Comparative Table

| Criterion            | Queryable Encryption  | CSFLE                 | Client-Side Encryption | Encryption at Rest      |
|----------------------|-----------------------|-----------------------|------------------------|-------------------------|
| **Performance**      | High (Supports Queries Directly) | Moderate (Queryable with Deterministic) | High (Simple Encryption) | High (Hardware-Level)   |
| **Ease of Use**      | Moderate (Requires Setup) | Moderate (Requires KMS Setup) | High (Direct Application Encryption) | High (Automatic, Managed) |
| **Data Security**    | High (Data remains encrypted) | High (Strict Client Control) | High (Strong Application Control) | Moderate (Data Encrypted at Storage) |
| **Strength Against Attacks** | High (Unique encryption for each query) | High (Data Sovereignty) | Moderate (Depends on Implementation) | Moderate (Physical Level Only) |
| **Case Usage**       | Query-heavy environments with sensitive data | Typically needed for sensitive PII fields | Generic application-level encryption | Environments focused on data persistence and compliance |


### Other considerations 
Client-Side Encryption (CSE) and Encryption at Rest are two distinct strategies used to secure data within a MongoDB environment, particularly when using Node.js. Let's explore how to implement these techniques and understand their differences using the document content provided and some examples.

- Provided by MongoDB enterprise versions, encrypts all data stored on disk, ensuring that any data accessed directly from storage devices remains encrypted.
- Does not affect the application logic as it’s handled by MongoDB itself.
- Encryption at Rest doesn't require changes to the application code like CSFLE does, but you'd configure your MongoDB server to enable it using configurations or by leveraging MongoDB Atlas, which offers encryption at rest by default.

| Feature/Aspect      | CSFLE                                      | Encryption at Rest               |
|---------------------|--------------------------------------------|----------------------------------|
| **Purpose**         | Encrypt fields before sending to DB        | Encrypt all stored data on disk  |
| **Query Capability**| Queries on deterministic fields            | N/A                              |
| **Server Version**  | 4.2 and newer                              | Only Enterprise or Atlas-managed |
| **Key Management**  | Client-side keys (KMS or local key)        | Server managed (transparent)     |
| **Use Cases**       | Fine-grained field protection              | Broad compliance, disk protection|
| **Setup Complexity**| Moderate due to key management             | Integrated, minimal setup        |


**Client-Side Encryption (CSE) vs. Client-Side Field Level Encryption (CSFLE):**

Both CSE and CSFLE involve encrypting data on the client side before it is sent to the server. They are part of MongoDB's broader security measures, but they have distinct features:


### Comparative Analysis:

| Aspect                | CSFLE                                     | General CSE                  | Queryable Encryption                 |
|-----------------------|-------------------------------------------|------------------------------|--------------------------------------|
| **Scope**             | MongoDB-specific API for field encryption | Broad, non-specific to MongoDB | MongoDB datasets with query capability |
| **Algorithm**         | Deterministic/Random provided by MongoDB  | User chosen from libraries   | Automatically managed by MongoDB      |
| **Querying**          | Deterministic allows queries              | Variable based on algorithm  | Full support for equality queries     |
| **Security Scope**    | Encrypts data before reaching the server  | Depends on user setup        | Strong encryption with unique values  |
| **Ease of Use**       | Requires MongoDB driver configuration     | Flexible, can be complex     | Simplified querying on encrypted fields|

Based on these distinctions:
- **CSFLE** is perfect when specific fields need encryption without exposing server admins to plaintext.
- **Client-Side Encryption** allows broader flexibility but may require more effort to integrate securely.
- **Queryable Encryption** is suitable for applications that need to perform secure operations on encrypted data 
without prior decryption.
- Use **CSFLE** when you need specific fields encrypted before reaching the server, especially if you require query capabilities on sensitive data.
- Use **Encryption at Rest** to ensure compliance and protect data from direct disk access without changing application logic, ideal for comprehensive data protection with less granularity.


### Examples of Usage

1. **Queryable Encryption Example**: Encrypt specific fields to ensure compliance and allow secure querying capabilities.
    ```javascript
    const { MongoClient } = require('mongodb');
    
    async function runQueryableEncryption() {
      const client = new MongoClient('mongodb://localhost:27017', {
        autoEncryption: {
          keyVaultNamespace: 'encryption.__keyVault',
          kmsProviders: { local: { key: Buffer.from(process.env.LOCAL_KEY, 'base64') } }
        }
      });
      
      await client.connect();
      const db = client.db('encryptedDb');
      const collection = db.collection('encryptedData');
  
      // Insert encrypted data
      await collection.insertOne({ sensitiveField: 'Sensitive123' });
      
      // Query encrypted data
      const doc = await collection.findOne({ sensitiveField: 'Sensitive123' });
      console.log('Queried Document:', doc);
      await client.close();
    }
  
    runQueryableEncryption();
    ```

2. **CSFLE with Local Key Management Example**: Insert and decrypt client-side managed encrypted data.
    ```javascript
    const { MongoClient, ClientEncryption } = require('mongodb');
    const fs = require('fs');
    
    const localKey = fs.readFileSync('local-keyfile');
    const client = new MongoClient('mongodb://localhost:27017', {
      autoEncryption: {
        keyVaultNamespace: 'encryption.__keyVault',
        kmsProviders: { local: { key: localKey } }
      }
    });
    
    async function runCSFLE() {
      await client.connect();
      const db = client.db('test');
      const encryption = new ClientEncryption(client, {
        keyVaultNamespace: 'encryption.__keyVault',
        kmsProviders: { local: { key: localKey } }
      });

      const keyId = await encryption.createDataKey('local');

      const encryptedValue = await encryption.encrypt('SensitiveData', { keyId, algorithm: 'AEAD_AES_256_CBC_HMAC_SHA_512-Deterministic' });

      await db.collection('encryptedData').insertOne({ sensitiveField: encryptedValue });

      const doc = await db.collection('encryptedData').findOne();
      const decryptedValue = await encryption.decrypt(doc.sensitiveField);
      console.log('Decrypted Value:', decryptedValue.toString());

      await client.close();
    }

    runCSFLE();
    ```

### Conclusion

The choice between these methods depends on specific application needs—Queryable Encryption is best for direct, secure querying; CSFLE offers high-level data privacy; Client-Side Encryption is versatile for app-level encryption; and Encryption at Rest safeguards stored data regardless of external access. Each has unique strengths and weaknesses in terms of performance, ease of use, security, and attack resilience.  

## Topics 
- [Client-Side Encyption](./encryption.cse.md)
- [CSFLE Encyption](./encryption.csfle.md)
- [Queryable Encyption](./encryption.queryable.md)

## References  
- [In-Use Encryption Tutorial](https://www.mongodb.com/docs/compass/current/in-use-encryption-tutorial/)
- [In-Use Encryption](https://mongodb.com/docs/languages/python/pymongo-driver/current/security/in-use-encryption/) 
- [View Documents](https://mongodb.com/docs/compass/current/documents/view/)
- [Set Validation Rules for Your Schema](https://mongodb.com/docs/compass/current/validation/)  
- [Mask Sensitive Fields](https://www.practical-mongodb-aggregations.com/examples/securing-data/mask-sensitive-fields)  
- [SOLUTIONS](https://www.mongodb.com/solutions/solutions-library/hasura-ddn-fintech)  
- [Archive Pattern](https://mongodb.com/docs/manual/data-modeling/design-patterns/archive/)  
- [Vector Quantization](https://mongodb.com/docs/atlas/atlas-vector-search/vector-quantization/)
