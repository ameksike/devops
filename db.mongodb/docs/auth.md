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

**References**

[Getting Started With Atlas Stream Processing Security](https://www.mongodb.com/developer/products/atlas/getting-started-atlas-stream-processing-security)
[#ask-devprod-infrastructure (@Geoffrey Mishkin 2024-06-10T21:27:43.000)](https://mongodb.slack.com/archives/C0V7VEU15/p1718054863507339?thread_ts=1718054863.507339&cid=C0V7VEU15)
[MongoDB Atlas Security.pdf](https://drive.google.com/file/d/1nEvR02CpNKicGIyO59PaQDSWN9N6WwTS/view?usp=drivesdk)
[Using MongoDB Atlas in GxP Systems.pdf](https://drive.google.com/file/d/1SP48Rxk6EG5OIaRsesj21-RA-bJdAPWu/view?usp=drivesdk)
[Okta Powers Natural Language Requests with Atlas Vector Search](https://www.mongodb.com/solutions/customer-case-studies/okta)
[MongoDB Atlas Search Powers the Albertsons Promotions Engine](https://www.mongodb.com/customers/albertsons)
[Picap Accelerates Journey From Startup to Million-dollar Company with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/picap)
[Using MongoDB Atlas & Atlas Search to Help Physical Retailers Go Digital](https://www.mongodb.com/solutions/customer-case-studies/nextar)
[Indeed Reduces Costs by 27% in 6 Months with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/indeed)
[MongoDB & The Knot Worldwide: Optimized performance, Zero Downtime](https://www.mongodb.com/customers/knot-worldwide)
[Shipium Boosts Efficiency and Optimizes Carrier Selection with MongoDB](https://www.mongodb.com/solutions/customer-case-studies/shipium)
[Fz Sports Improves Performance by 100% While Reducing Costs](https://www.mongodb.com/solutions/customer-case-studies/fz-sports)
[My Muscle Chef relies on MongoDB Atlas and AWS to build the infinite customer ecosystem](https://www.mongodb.com/solutions/customer-case-studies/my-muscle-chef)
[mongohouse/docs/environment.md](https://github.com/10gen/mongohouse/tree/master/docs/environment.md)
[mongohouse/docs/environment.md](https://github.com/10gen/mongohouse/tree/master/docs/environment.md)
[Define Data Access Permissions - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/rules/)
[mongohouse/internal/domains/query/storeaccess/README.md](https://github.com/10gen/mongohouse/tree/master/internal/domains/query/storeaccess/README.md)
[mongohouse/internal/domains/query/storeaccess/README.md](https://github.com/10gen/mongohouse/tree/master/internal/domains/query/storeaccess/README.md)
[App Configuration - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/reference/config/)
[Sync Settings - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/sync/configure/sync-settings/)
[Updates with Aggregation Pipeline - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/tutorial/update-documents-with-aggregation-pipeline/)
[Configure Maintenance Window - Atlas - MongoDB Docs](https://www.mongodb.com/docs/atlas/tutorial/cluster-maintenance-window/)
[Comparison/Sort Order - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/reference/bson-type-comparison-order/)
[Develop & Deploy Apps - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/apps/)
[Configure and Enable Atlas Device Sync - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/sync/configure/enable-sync/)
[App Services CLI - Atlas App Services - MongoDB Docs](https://www.mongodb.com/docs/atlas/app-services/cli/)
[Read Concern - Database Manual v8.0 - MongoDB Docs](https://www.mongodb.com/docs/manual/reference/read-concern/)
[Atlas Device SDK for the Web - Atlas Device SDKs - MongoDB Docs](https://www.mongodb.com/docs/atlas/device-sdks/web/)