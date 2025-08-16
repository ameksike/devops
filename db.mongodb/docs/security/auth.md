### OAuth/OIDC Authentication in MongoDB Atlas (Version Reference: Supports OIDC as of **MongoDB Atlas 2021 updates**)

MongoDB Atlas integrates with OpenID Connect (OIDC) using OAuth 2.0 standards, allowing customers to authenticate users through federated identity providers (IdPs) such as Okta, Azure AD, Ping Identity, and others. **MongoDB itself does not act as an IdP**; it functions as a service provider (SP) and relies on external IdPs to perform the authentication and authorization processes. Below is an explanation covering the following aspects:

---

#### **Supported OAuth Flows in MongoDB Atlas**
1. **Authorization Code Flow**  
   - **Use Case**: The most secure and widely used flow for server-to-server communication. Primarily meant for MongoDB Atlas UI integrations or control plane interactions rather than direct database access.  
   - **Goal**: Obtain long-lived tokens after exchanging an authorization code received from the IdP.  
   - **Supported in MongoDB Atlas**: Used for authenticating via the Atlas UI/API/CLI (control plane), enabling users to access Atlas organizational resources.  

2. **OIDC for Database User Authentication**  
   - **Use Case**: Allows IdP integration for authenticating **database users** who access MongoDB clusters.
   - MongoDB Atlas enables access via assigned roles derived from OIDC claims for group-based policies (e.g., Okta groups). This avoids manual creation of users/provisioning logical passwords at each Atlas project.
   - **Goal**: Enable database access for users via federated group claims.
   - **Supported Environments**: Atlas clusters; specific group claims from the IdP are mapped to database roles.

---

#### **Authentication in Context**
1. **Control Plane (Atlas UI/API/CLI Integration)**  
   - Authentication to the administrative UI interface (Atlas organization setup and project management).  
   - Primarily uses **SAML/OIDC** for authenticating organization-level admins via OAuth Authorization Code flow.

2. **Database Plane (Cluster-Level Access)**  
   - Authentication for database operations through MongoDB drivers (e.g., Java Driver, Node.js Driver).  
   - Supports OIDC-based database users to eliminate username/password-based SCRAM credentials.  
   - Integrates directly with your IdP for seamless role-based permission assignment.

---

#### **Comparative Table of OAuth/OIDC Support Across Editions**

| **Functionality**                  | **Community Edition** | **Enterprise Advanced** | **Atlas**                              |
|------------------------------------|------------------------|--------------------------|----------------------------------------|
| **Authentication via OAuth/OIDC**  | Not Supported          | Partial (Limited LDAP)   | Fully Supported                        |
| **Authorization Code Flow**        | Not Supported          | Not Supported            | Supported (Control Plane Management)   |
| **OIDC for Database Users**        | Not Supported          | Partial LDAP Proxy       | Fully Supported (Cluster Database Access) |
| **SAML Integration**               | Not Supported          | Supported (on-prem IdPs) | Fully Supported (Atlas Control Plane)  |

---

#### **Responsibilities of MongoDB as SP**
MongoDB, as a **service provider**, relies on external IdPs (e.g., Okta, Azure AD) and authorization servers to:
1. Issue authentication tokens (ID Tokens or access tokens via OAuth).
2. Validate token claims upon receipt.
3. Map claims (e.g., `groups`, `email`, `roles`) to appropriate MongoDB roles within a cluster.

---

#### **Responsibilities of the IdP**
The external **identity provider (IdP)** is responsible for:
1. Managing user authentication (login process).
2. Generating JWT tokens with valid claims.
3. Issuing and revoking tokens securely.
4. Supporting claim-based configuration for organizational/group mappings.

---

#### **Use Cases and Scenarios**
1. **Centralized Database Access Control**:
   - Use OIDC integration with Okta to manage database access for developers across multiple projects/orgs.  
   - Example: Map Okta groups to Atlas roles like `readWrite`, `admin`, and `backup`.

2. **Simplified Onboarding in Multi-Cloud Environments**:  
   - New developers gain access to clusters through group memberships in the IdP without manual user creation in MongoDB databases.

3. **Secure Role-Based Access for Admins**:
   - Atlas UI admins authenticate via OIDC/SAML without storing password information in Atlas.

4. **Temporary TLS/x.509 Database Access for Services**:
   - MongoDB internal services (e.g., Atlas Data Federation) use ephemeral x.509 certificates under OIDC workflows for backend tasks.

---

Here's a comprehensive schema for MongoDB connection strings in English, covering various authentication types with descriptions for better understanding:

## MongoDB Connection String Schema

A MongoDB connection string is a URI (Uniform Resource Identifier) that specifies how to connect to a MongoDB database. It contains various components that define the server address, port, authentication credentials, database to connect to, and other connection options.

### General Structure:

The basic structure of a MongoDB connection string is as follows:

`mongodb://[username:password@]host1[:port1][,host2[:port2],...]/[database][?options]`

Let's break down each component:

**1. `mongodb://` (Required - Protocol)**
   * **Description:** This is the required protocol prefix, indicating that it's a MongoDB connection string. For SRV records (DNS seed list), you would use `mongodb+srv://`.

**2. `[username:password@]` (Optional - Authentication Credentials)**
   * **Description:** This section contains the username and password for authentication.
   * **`username`:** The user account name used to authenticate to the MongoDB server.
   * **`password`:** The password for the specified user.
   * **Note:** This part is only included when authentication is required. If either username or password contains special characters (e.g., `:`, `@`, `/`, `?`, `#`, `[`, `]`, `!`, `$`, `&`, `'`, `(`, `)`, `*`, `+`, `,`, `;`, `=`, `~`), they must be URL-encoded.

**3. `host1[:port1][,host2[:port2],...]` (Required - Host(s) and Port(s))**
   * **Description:** Specifies the address(es) of the MongoDB server(s) or replica set members.
   * **`host`:** The hostname or IP address of the MongoDB server.
   * **`port` (Optional):** The port number on which the MongoDB server is listening. The default port is `27017`.
   * **Multiple Hosts:** For replica sets or sharded clusters, you can list multiple hosts separated by commas. The driver will attempt to connect to each in the order listed until a successful connection is established.

**4. `/[database]` (Optional - Database Name)**
   * **Description:** The name of the database to connect to. If not specified, the connection will default to the `test` database or the database specified in the `authSource` option.

**5. `[?options]` (Optional - Connection Options)**
   * **Description:** A query string containing various connection options, separated by `&`. These options control connection behavior, authentication settings, and more.

---

### Examples by Authentication Type:

Here are various examples demonstrating different authentication types and common connection options:

#### 1. No Authentication (Local Development/Test Environment)

* **Connection String:**
    `mongodb://localhost:27017/mydatabase`
* **Components:**
    * `mongodb://`: Protocol
    * `localhost:27017`: Host and Port
    * `/mydatabase`: Database Name
* **Description:** This is a basic connection to a MongoDB instance running on the local machine on the default port, without any authentication required. It connects to the `mydatabase` database.

#### 2. SCRAM-SHA-1/SHA-256 Authentication (Username/Password)

* **Connection String:**
    `mongodb://myuser:mypassword@localhost:27017/mydatabase?authSource=admin`
* **Components:**
    * `mongodb://`: Protocol
    * `myuser:mypassword@`: Username and Password
    * `localhost:27017`: Host and Port
    * `/mydatabase`: Database Name
    * `?authSource=admin`: Connection Option
* **Description:** This connects to a MongoDB instance with `myuser` and `mypassword` credentials.
    * **`authSource=admin`:** Specifies the database where the user is defined. In this common scenario, the user `myuser` exists in the `admin` database.

#### 3. Replica Set Connection with Authentication

* **Connection String:**
    `mongodb://myuser:mypassword@rs0.example.com:27017,rs1.example.com:27017,rs2.example.com:27017/mydatabase?replicaSet=myReplicaSet&authSource=admin`
* **Components:**
    * `mongodb://`: Protocol
    * `myuser:mypassword@`: Username and Password
    * `rs0.example.com:27017,rs1.example.com:27017,rs2.example.com:27017`: Multiple Hosts (Replica Set Members)
    * `/mydatabase`: Database Name
    * `?replicaSet=myReplicaSet&authSource=admin`: Connection Options
* **Description:** Connects to a replica set named `myReplicaSet` with three members. The user `myuser` authenticates against the `admin` database. The driver will automatically discover the primary and secondaries.

#### 4. SRV Record (DNS Seed List) Connection

* **Connection String:**
    `mongodb+srv://myuser:mypassword@cluster0.abcde.mongodb.net/mydatabase?retryWrites=true&w=majority`
* **Components:**
    * `mongodb+srv://`: SRV Protocol (indicates DNS seed list)
    * `myuser:mypassword@`: Username and Password
    * `cluster0.abcde.mongodb.net`: SRV Hostname (DNS record will resolve to actual server addresses)
    * `/mydatabase`: Database Name
    * `?retryWrites=true&w=majority`: Connection Options
* **Description:** This is common for MongoDB Atlas or cloud deployments. The driver queries DNS for SRV records associated with `cluster0.abcde.mongodb.net` to discover the cluster members.
    * **`retryWrites=true`:** Enables automatic retries for certain write operations in case of transient network errors or failovers.
    * **`w=majority`:** Specifies that write operations should be acknowledged by a majority of the replica set members.

#### 5. X.509 Certificate Authentication

* **Connection String:**
    `mongodb://localhost:27017/mydatabase?ssl=true&authMechanism=MONGODB-X509&sslClientCertificateKeyFile=/path/to/client.pem&sslCAFile=/path/to/ca.pem`
* **Components:**
    * `mongodb://`: Protocol
    * `localhost:27017`: Host and Port
    * `/mydatabase`: Database Name
    * `?ssl=true&authMechanism=MONGODB-X509&sslClientCertificateKeyFile=/path/to/client.pem&sslCAFile=/path/to/ca.pem`: Connection Options
* **Description:** Connects to a MongoDB instance using X.509 client certificate authentication over SSL/TLS.
    * **`ssl=true`:** Enables SSL/TLS encryption for the connection.
    * **`authMechanism=MONGODB-X509`:** Specifies the authentication mechanism as X.509.
    * **`sslClientCertificateKeyFile`:** Path to the client's certificate and private key file (often a `.pem` file).
    * **`sslCAFile`:** Path to the Certificate Authority (CA) file that signed the server's certificate.

#### 6. LDAP Authentication

* **Connection String:**
    `mongodb://ldapuser@localhost:27017/mydatabase?authMechanism=PLAIN&authSource=$external`
* **Components:**
    * `mongodb://`: Protocol
    * `ldapuser@`: Username (password is usually prompted separately or handled by the LDAP client configuration)
    * `localhost:27017`: Host and Port
    * `/mydatabase`: Database Name
    * `?authMechanism=PLAIN&authSource=$external`: Connection Options
* **Description:** Connects to a MongoDB instance configured to use LDAP for authentication.
    * **`authMechanism=PLAIN`:** Specifies the `PLAIN` authentication mechanism, which is often used for LDAP integration.
    * **`authSource=$external`:** Indicates that authentication will be handled by an external source (LDAP).

#### 7. Kerberos (GSSAPI) Authentication

* **Connection String:**
    `mongodb://myuser%40EXAMPLE.COM@localhost:27017/mydatabase?authMechanism=GSSAPI&authSource=$external&authMechanismProperties=SERVICE_NAME:mongodb`
* **Components:**
    * `mongodb://`: Protocol
    * `myuser%40EXAMPLE.COM@`: Username (URL-encoded `@`)
    * `localhost:27017`: Host and Port
    * `/mydatabase`: Database Name
    * `?authMechanism=GSSAPI&authSource=$external&authMechanismProperties=SERVICE_NAME:mongodb`: Connection Options
* **Description:** Connects to a MongoDB instance using Kerberos authentication.
    * **`authMechanism=GSSAPI`:** Specifies the GSSAPI (Kerberos) authentication mechanism.
    * **`authSource=$external`:** Indicates that authentication will be handled by an external source.
    * **`authMechanismProperties=SERVICE_NAME:mongodb`:** Specifies Kerberos-specific properties, such as the service name.

---

This comprehensive schema and examples should provide a clear understanding of MongoDB connection strings and their various components based on different authentication methods.


**References**
- [Getting Started With Atlas Stream Processing Security](https://www.mongodb.com/developer/products/atlas/getting-started-atlas-stream-processing-security)
- [#ask-devprod-infrastructure (@Geoffrey Mishkin 2024-06-10T21:27:43.000)](https://mongodb.slack.com/archives/C0V7VEU15/p1718054863507339?thread_ts=1718054863.507339&cid=C0V7VEU15)
- [MongoDB Atlas Security.pdf](https://drive.google.com/file/d/1nEvR02CpNKicGIyO59PaQDSWN9N6WwTS/view?usp=drivesdk)
- [Using MongoDB Atlas in GxP Systems.pdf](https://drive.google.com/file/d/1SP48Rxk6EG5OIaRsesj21-RA-bJdAPWu/view?usp=drivesdk)
- [Okta Powers Natural Language Requests with Atlas Vector Search](https://www.mongodb.com/solutions/customer-case-studies/okta)
- [MongoDB Atlas Search Powers the Albertsons Promotions Engine](https://www.mongodb.com/customers/albertsons)
- [Picap Accelerates Journey From Startup to Million-dollar Company with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/picap)
- [Using MongoDB Atlas & Atlas Search to Help Physical Retailers Go Digital](https://www.mongodb.com/solutions/customer-case-studies/nextar)
- [Indeed Reduces Costs by 27% in 6 Months with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/indeed)
- [MongoDB & The Knot Worldwide: Optimized performance, Zero Downtime](https://www.mongodb.com/customers/knot-worldwide)
- [Shipium Boosts Efficiency and Optimizes Carrier Selection with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/shipium)
- [Fz Sports Improves Performance by 100% While Reducing Costs](https://www.mongodb.com/solutions/customer-case-studies/fz-sports)
- [My Muscle Chef relies on MongoDB Atlas and AWS to build the infinite customer ecosystem](https://www.mongodb.com/solutions/customer-case-studies/my-muscle-chef)
- [mongohouse/docs/environment.md](https://github.com/10gen/mongohouse/tree/master/docs/environment.md)
- [mongohouse/docs/environment.md](https://github.com/10gen/mongohouse/tree/master/docs/environment.md)
- [Define Data Access Permissions - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/rules/)
- [mongohouse/internal/domains/query/storeaccess/README.md](https://github.com/10gen/mongohouse/tree/master/internal/domains/query/storeaccess/README.md)
- [mongohouse/internal/domains/query/storeaccess/README.md](https://github.com/10gen/mongohouse/tree/master/internal/domains/query/storeaccess/README.md)
- [App Configuration - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/reference/config/)
- [Sync Settings - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/sync/configure/sync-settings/)
- [Updates with Aggregation Pipeline - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/tutorial/update-documents-with-aggregation-pipeline/)
- [Configure Maintenance Window - Atlas - MongoDB Docs](https://www.mongodb.com/docs/atlas/tutorial/cluster-maintenance-window/)
- [Comparison/Sort Order - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/reference/bson-type-comparison-order/)
- [Develop & Deploy Apps - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/apps/)
- [Configure and Enable Atlas Device Sync - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/sync/configure/enable-sync/)
- [App Services CLI - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/cli/)
- [Read Concern - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/reference/read-concern/)
- [Atlas Device SDK for the Web - Atlas Device SDKs - MongoDB Docs](https://www.mongodb.com/docs/atlas/device-sdks/web/)
- [Password Rollover: Having two valid Database User Passwords at the same time???](https://database-heartbeat.com/2021/07/27/having-two-valid-database-user-passwords-at-the-same-time/)