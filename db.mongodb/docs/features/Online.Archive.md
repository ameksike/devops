### What is MongoDB Atlas's Online Archive Feature?

**MongoDB Atlas Online Archive** is a built-in data tiering feature in MongoDB Atlas. It enables organizations to seamlessly **offload infrequently accessed (cold) data** from live operational Atlas clusters to **cost-effective cloud object storage**, while still allowing query access to the archived data alongside the live Atlas cluster data. This is especially useful for managing large, growing datasets and optimizing cluster size and costs without losing the ability to access archived data.

---

### **How Does it Work?**

1. **Archiving Rule Setup:**
   - Users define an **archiving rule** based on criteria such as data age, retention policies, or infrequent access (e.g., archiving documents older than 12 months).
   - Every five minutes (default), Atlas evaluates the criteria and moves eligible data to **MongoDB-managed cloud object storage**.

2. **Unified Query Access via Atlas Data Federation:**
   - Archived data is stored in **read-only object storage** but remains accessible as part of the larger dataset.
   - Using **Atlas Data Federation**, users can query both live and archived data via a unified endpoint—just like they query active data in their cluster. This eliminates the need for manual reprocessing or migration workflows.

3. **Cold Data Query Access:**
   - Though archived data is stored in a read-only format, it is accessible alongside live cluster data for analytics and reporting.

---

### **How to Set Up and Activate Online Archive?**

1. **Pre-Requisites:**
   - The **Online Archive feature is supported on clusters running MongoDB 3.6 or newer**, starting from Atlas **M10 or higher** tiers.
   - The database must have data loaded, for example, using sample datasets like `sample_mflix.comments`.

2. **Steps to Activate:**
   - Go to the **MongoDB Atlas UI**, select your cluster, and click on the **Online Archive tab**.
   - Click **Configure Online Archive** and define an **archiving rule** (e.g., archive all documents older than 12 months).
   - Save the configuration, and the archival process will automatically start.

3. **Query Access Setup:**
   - Use the Atlas Data Federation connection to access archived data alongside live data.
   - Atlas provides connection strings to exclusively query the archived data, or unified connection strings for combined live and archived data.

---

### **Key Benefits of the Online Archive Feature**

1. **Cost-Effective Data Management:**
   - Cold data is moved to cloud object storage, reducing the size and costs of the live cluster (e.g., lower disk utilization and cluster compute resources).

2. **Performance Optimization:**
   - Moving aged-out or infrequently accessed data minimizes the load on the main Atlas cluster, improving performance and reducing resource contention for active workloads.

3. **Efficient Data Retention Policies:**
   - Guarantees long-term retention of historical data in secure cloud object storage while adhering to compliance requirements.

4. **Unified Query Experience:**
   - By using Atlas Data Federation, users retain the ability to query both live and archived data together through a single endpoint, simplifying analytics workflows.

5. **Fully-Managed Archiving:**
   - No need for manual data extraction, movement, or complex pipeline configurations. Atlas Online Archive handles everything automatically.

---

### **Limitations of Online Archive**
1. **Read-Only Storage:**
   - Archived data is stored in read-only mode. It cannot be modified or written to, ensuring data integrity and compliance with archival policies.
   
2. **Object Storage Usage:**
   - Archived data retrieval relies on object storage mechanisms, which may result in slightly slower queries compared to live operational data.

3. **Cluster Size Requirements:**
   - Online Archive is available for **M10+ Atlas clusters only** and is not supported in smaller cluster tiers or free-tier clusters.

---

### **Conclusion**

MongoDB Atlas’s Online Archive allows organizations to optimize storage costs by archiving cold data while providing unified query access through Atlas Data Federation. It automates data tiering, enhances operational cluster performance, and secures archived data using MongoDB-managed cloud object storage.



### **Can MongoDB Atlas's Online Archive Feature Be Used in Enterprise Advanced and Community Edition?**

No, **MongoDB Atlas's Online Archive feature** is exclusive to **MongoDB Atlas**, the fully managed cloud service. It is not available in **MongoDB Community Edition** or **MongoDB Enterprise Advanced (EA)**, as it relies on the Atlas platform's data federation and cloud object storage capabilities. Users running MongoDB Community Edition or EA would need to build their own custom solutions, such as ETL pipelines or external archiving processes, to achieve similar functionality.

---

### **Optimizing Old Data for Disk Space Reduction (Documents older than six months)**

If you want **documents older than 6 months** to be optimized for reduced disk usage, the process depends on whether you're using **MongoDB Atlas**, **Enterprise Advanced**, or **Community Edition:**

#### **Solution in MongoDB Atlas** (Using Online Archive):
MongoDB Atlas makes this task straightforward with the **Online Archive feature**, which automatically moves older, infrequently accessed data to a cost-efficient cloud object storage while retaining unified query access. 

Here’s how to proceed:

##### **Example Scenario: Your collection `my_data` contains documents with a `created_at` field. You want to archive documents older than 6 months.**

1. **Enable Online Archive:**
   - Navigate to the Atlas UI:
     - Select your cluster and access the **Online Archive** tab.
   - Define an archiving rule:
     ```plaintext
     Archive all documents where `created_at` is older than 6 months.
     ```
   - Example Configuration:
     - **Criteria**: `{ created_at: { $lt: new Date(new Date() - (6 * 30 * 24 * 60 * 60 * 1000)) } }`.
     - **Frequency**: Evaluate every 5 minutes.

2. **Unified Query Access:**
   - Use **Atlas Data Federation** to query both live and archived data. You can query the data seamlessly with the same connection string as if it were part of your main database:
     ```javascript
     db.my_data.find({ created_at: { $lt: new Date(new Date() - (6 * 30 * 24 * 60 * 60 * 1000)) } });
     ```

3. **Impact:**
   - Archived data is stored on cloud object storage (e.g., AWS S3, Google Cloud Storage), reducing the live cluster's disk space usage.
   - No changes are required in application logic; queries automatically leverage archived data when required.

---

#### **Solution in Enterprise Advanced or Community Edition** (Using WiredTiger Compression):
If Online Archive is unavailable, you can still optimize your disk space by leveraging **WiredTiger compression** to reduce the size of old data while storing it in the live database. You need to reorganize old data within the same collection or a separate collection.

##### **Example Steps:**

1. **Reorganize Old Data in a Separate Collection:**
   - Move documents older than 6 months to a new collection (`archived_data`) to better manage and compress inactive data. This reduces the active collection's operational load.
     ```javascript
     const sixMonthsAgo = new Date(new Date() - (6 * 30 * 24 * 60 * 60 * 1000));
     db.my_data.aggregate([
       { $match: { created_at: { $lt: sixMonthsAgo } } },
       { $out: "archived_data" }
     ]);
     db.my_data.deleteMany({ created_at: { $lt: sixMonthsAgo } });
     ```

2. **Enable Compression for Both Collections:**
   - WiredTiger uses **Snappy** by default, but if you want higher compression, switch the old collection (`archived_data`) to **Zstd** or **Zlib** during creation:
     ```javascript
     db.createCollection("archived_data", {
       storageEngine: {
         wiredTiger: {
           configString: "block_compressor=zstd"
         }
       }
     });
     ```

3. **Query Logic:**
   - Update your application to query both collections, or adjust your logic to fetch data from the archived collection when searching for older records.

4. **Disk Space Reduction Impact:**
   - Compression reduces the storage size of both live data (`my_data`) and archived data (`archived_data`) directly on disk. However, this approach requires manual management of data pipelines and querying.

---

#### **Comparing Online Archive and WiredTiger Solutions**

| **Feature**                          | **MongoDB Atlas Online Archive**             | **WiredTiger Compression (EA/Community)**   |
|--------------------------------------|---------------------------------------------|---------------------------------------------|
| Automation                           | Fully automated archival and query access.  | Manual reorganization of data required.     |
| Disk Space Reduction                 | Data moved to cloud object storage.         | On-disk data compression only.              |
| Query Access                         | Unified via Atlas Data Federation.          | Requires querying multiple collections.     |
| Cost Efficiency                      | Reduces live cluster size; lowers costs.    | Compression saves disk space but data remains in live cluster. |
| Data Migration                       | No migration needed; operates on same collection. | Migration to separate collections possible.  |
| Complexity                           | Simple and automated.                       | Higher operational complexity.               |

---

### **Performance and Environment Setup**

#### **Changes Needed for MongoDB Atlas:**
If you're using MongoDB Atlas, no application logic changes are required:
1. **Enable Online Archive** directly in the Atlas UI.
2. **Ensure queries use Atlas Data Federation** for unified access across archived and live data.

#### **Changes for Enterprise Advanced/Community Edition:**
1. **Create a New Collection for Old Data** (`archived_data`).
2. **Enable WiredTiger Compression** for the `archived_data` collection.
3. **Update Application Logic**:
   - Modify queries to fetch data from multiple collections based on the date range.

---

### **Which Approach to Use?**

1. **Online Archive (Atlas):**
   - Best suited for **cloud-managed environments** where automation is preferred.
   - Reduces operational overhead and simplifies data storage costs.

2. **WiredTiger Compression (EA/Community):**
   - Ideal for **on-premise/self-managed deployments**.
   - Requires more active management but achieves similar disk space savings.

---

### **Example Using MongoDB Atlas Online Archive**

Assume you’re working with the `sample_mflix.comments` collection and want to archive data older than 6 months.

#### **Setup:**
1. In the Atlas UI, enable an Online Archive rule:
   ```plaintext
   Archive documents from `sample_mflix.comments` where `created_at` is older than 6 months.
   ```

2. **Unified Query Access:**
   - Query remains unchanged:
     ```javascript
     db.sample_mflix.comments.find({ created_at: { $lt: sixMonthsAgo } });
     ```

Result: MongoDB Atlas automatically archives cold data to cloud storage while maintaining access through the same query API.

---

### **Example Using WiredTiger in EA/Community**

Assume you have a self-managed MongoDB setup and need to compress old data.

#### **Steps:**
1. Move old data to an `archived_comments` collection:
   ```javascript
   db.sample_mflix.comments.aggregate([
     { $match: { created_at: { $lt: sixMonthsAgo } } },
     { $out: "archived_comments" }
   ]);
   ```

2. Compression:
   ```javascript
   db.createCollection("archived_comments", {
     storageEngine: {
       wiredTiger: {
         configString: "block_compressor=zstd"
       }
     }
   });
   ```

3. Update application to handle queries across multiple collections:
   ```javascript
   if(customQuery.date < sixMonthsAgo) {
     db.archived_comments.find(customQuery);
   } else {
     db.sample_mflix.comments.find(customQuery);
   }
   ```

Result: You achieve reduced disk storage but require manual reorganization and collection management.

---

### Key Notes:
- **MongoDB Atlas's Online Archive** simplifies automation and is ideal for modern cloud environments.
- **WiredTiger Compression** offers flexibility for self-managed solutions but requires more hands-on configuration.

**References**

- [How to Manage Data at Scale With MongoDB Atlas Online Archive](https://www.mongodb.com/developer/products/atlas/manage-data-at-scale-with-online-archive)
- [How to Migrate PostgreSQL to MongoDB With Confluent Kafka](https://www.mongodb.com/developer/products/atlas/change-streams-with-kafka)
- [MongoDB Performance Tuning Questions](https://www.mongodb.com/developer/products/mongodb/performance-tuning-tips)
- [Comparing MongoDB vs PostgreSQL    ](https://www.mongodb.com/resources/compare/mongodb-postgresql)
- [Comparing MongoDB vs PostgreSQL    ](https://www.mongodb.com/resources/compare/mongodb-postgresql)
- [How to Deploy MongoDB on Heroku](https://www.mongodb.com/developer/products/atlas/use-atlas-on-heroku)
- [Leveraging Database Observability at MongoDB: Real-Life Use Case](https://www.mongodb.com/blog/post/leveraging-database-observability-mongodb-real-life-use-case)