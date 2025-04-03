
## Docs 

```js
db.help()
```

### Data
- JSON
- BJSON: 
    - Optimized for storage, retrieval, and transition across the wire
    - it is more sure than plain text JSON
    - it includes more data types

### Atlas
The MongoDB database is a core element of MongoDB Atlas, which is a multi-cloud developer data platform. The MongoDB database is the foundation of MongoDB Atlas. The additional functionality that Atlas offers—such as full-text search, data visualization, data lake storage, and mobile device sync—are built on top of data stored in cloud-hosted MongoDB database deployments.

### Data Modeling
- Embedded documents store related data in a single document.
- Reference relationships store data by linking references in one document to another document

### Common schema anti-patterns
- Massive arrays
- Massive number of collections 
- Bloated documents 
- Unnecessary indexes
- Query without indexes
- Data that's accessed together, but stored in different collections 

### Tools 
- Atlas 
    - Data Explorer: The Schema Anti-Patterns tab highlights any issues in the collection and provides details to resolve them. You can improve your schema by resolving the anti-patterns that are shown.
    ![](../rsc/data.explore.jpg)
    - Performance Advisor
    ![](../rsc/performance.tool.jpg)

### Connection String
- Examples:
    - Connect with the MongoDB Shell: `mongosh "mongodb+srv://mdb-training-cluster.swnn5.mongodb.net/myFirstDatabase" --apiVersion 1 --username MDBUser`
    - Connect your application: `mongodb+srv://MDBUser:<password>@mdb-training-cluster.swnn5.mongodb.net/myFirstDatabase?retryWrites=true&w=majority`
    - Connect using MongoDB Compass: `mongodb+srv://MDBUser:<password>@mdb-training-cluster.swnn5.mongodb.net/test`

### Topics
- [General Database Comparison](./comparison.md)
- [Security Database Comparison](./comparison.security.md)
- [CRUD & data manipulation](./crud.md) 
- [Aggregations](./agregation.md) 
- [Transactions](./transactions.md) 
- [Indexes](./indexes.md) 
- [Schema](./schema.md)
- [Encyption](./encryption.md)
    - [ODM Encyption](./encyption.odm.md.md)
    - [CSFLE Encyption](./encryption.csfle.md)
    - [Queryable Encyption](./encryption.queryable.md)
- CIDR notation: CIDR notation represents an IP address and a suffix that indicates network identifier bits in a specified format. For example, you could express 192.168. 1.0 with a 22-bit network identifier as 192.168. 1.0/22.

## References 
- [Learners journey](https://learn.mongodb.com/pages/learners-journey)
- [Usage examples](https://www.mongodb.com/docs/drivers/node/current/usage-examples/)
- [Drivers](https://www.mongodb.com/docs/drivers/)
- [Introduction to MongoDB Data Modeling](https://learn.mongodb.com/learn/course/introduction-to-mongodb-data-modeling/conclusion/learn?client=customer&page=2)
    - [Model One-to-Many Relationships with Document References](https://www.mongodb.com/docs/manual/tutorial/model-referenced-one-to-many-relationships-between-documents/?_ga=2.64006886.810066485.1665291537-836515500.1666025886)
- [Deploy a Free Cluster](https://www.mongodb.com/docs/atlas/tutorial/deploy-free-tier-cluster/?utm_source=Iterable&utm_medium=email&utm_campaign=campaign_7715097)
- Security
    - [Technical and Organizational Security Measures](https://www.mongodb.com/legal/customer-service-agreement/technical-and-organizational-security-measures)
    - [MongoDB Security Bulletins](https://www.mongodb.com/resources/products/mongodb-security-bulletins)
    - [Authentication Mechanism (SCRAM)](https://www.mongodb.com/docs/manual/core/security-scram/)
    - [Atlas Administration API Authentication](https://www.mongodb.com/docs/atlas/api/api-authentication/)
    - [The Federal Information Processing Standard (FIPS)](https://www.mongodb.com/docs/manual/tutorial/configure-fips/#std-label-fips-overview)
    - [Implement Field Level Redaction](https://www.mongodb.com/docs/manual/tutorial/implement-field-level-redaction/)