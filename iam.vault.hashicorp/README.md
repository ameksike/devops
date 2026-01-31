
# Running HashiCorp Vault in Docker (Dev Mode) on Windows

This document describes how to run a **HashiCorp Vault** server in **development mode** using **Docker Compose** on Windows, and how to access it from the browser and the CLI. It also includes a **very simple Node.js example** that reads credentials from Vault.

> **Note:** Development mode is **not for production**. It runs entirely in memory, starts unsealed, and uses a fixed root token.

---

## 1. Prerequisites

1. **Docker Desktop for Windows** installed  
   Download and install from:  
   [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

2. **Docker Desktop running**
   - Start *Docker Desktop* from the Start Menu.
   - Wait until it shows **“Docker is running”**.
   - Verify from a terminal (PowerShell or CMD):

     ```bash
     docker version
     ```

     You should see both **Client** and **Server** sections without connection errors.

---

## 2. Project Structure

Create a directory for your Vault lab, for example:

```bash
C:\data\dev\check\vault
```

From this directory, you will create the `docker-compose.yml` file.

---

## 3. `docker-compose.yml` for Vault Dev Mode

In `C:\data\dev\check\vault\docker-compose.yml`:

```yaml
version: "3.8"

services:
  vault:
    image: hashicorp/vault:1.21.2
    container_name: vault-dev
    restart: unless-stopped
    ports:
      - "8200:8200"
    environment:
      VAULT_DEV_ROOT_TOKEN_ID: "root"
      VAULT_DEV_LISTEN_ADDRESS: "0.0.0.0:8200"
    cap_add:
      - IPC_LOCK
    command: ["vault", "server",
              "-dev",
              "-dev-root-token-id=root",
              "-dev-listen-address=0.0.0.0:8200"]
```

Key points:

- **Port 8200** is exposed on the host (`localhost:8200`).
- Vault runs in **dev mode**, in memory, and starts:
  - **Unsealed**
  - With **root token** set to `root`

---

## 4. Starting Vault with Docker Compose

From `C:\data\dev\check\vault`:

```bash
docker compose up -d
```

This will:

- Pull the `hashicorp/vault` image (if not present).
- Start the `vault-dev` container in the background.

To see the logs:

```bash
docker compose logs -f
```

You should see output similar to:

- `Vault v1.21.2`
- Listener on `0.0.0.0:8200`
- Warning about **dev mode** being enabled
- Display of **Unseal Key** and **Root Token: root**

---

## 5. Accessing Vault from the Browser (Web UI)

1. Open your browser and go to:

   ```text
   http://localhost:8200
   ```

2. In the login screen:
   - **Method:** Token
   - **Token:** `root`

3. Click **Sign in**. You should now see the Vault UI.

---

## 6. Accessing Vault via CLI Inside the Container

To run Vault CLI commands inside the container:

```bash
docker exec -it vault-dev sh
```

Inside the container shell:

```bash
export VAULT_ADDR='http://0.0.0.0:8200'

vault status
vault login root
```

If the login is successful, you can start enabling secrets engines, auth methods, etc.

---

## 7. Accessing Vault via CLI from the Windows Host

If you have the `vault` CLI installed on Windows, you can connect directly to the container from your host.

### PowerShell

```powershell
$env:VAULT_ADDR = "http://127.0.0.1:8200"

vault status
vault login root
```

### CMD

```cmd
set VAULT_ADDR=http://127.0.0.1:8200

vault status
vault login root
```

---

## 8. Notes About Dev Mode

In this setup, Vault is running with:

- **Storage:** in-memory (`inmem`)
- **Unseal:** already unsealed (no `vault operator init` / `unseal` needed)
- **Root token:** hard-coded to `root`
- **No TLS:** using plain HTTP on `localhost:8200`

Implications:

- **All data is lost** when the container stops or restarts.
- **Security is minimal**: suitable only for **development, testing, and learning**.
- Do **not** use this configuration for production or sensitive data.

---

## 9. Stopping and Cleaning Up

To stop the Vault container:

```bash
docker compose down
```

This will:

- Stop and remove the `vault-dev` container.
- Since dev mode uses in-memory storage, **no secrets are persisted**.

---

## 10. Creating a Test Secret in Vault (KV Engine)

Before using Node.js, create a simple secret in Vault.

1. Enter the container:

   ```bash
   docker exec -it vault-dev sh
   ```

2. Make sure the address is set:

   ```bash
   export VAULT_ADDR='http://0.0.0.0:8200'
   vault login root
   ```

3. Create a secret for your app, for example under `secret/myapp`:

   ```bash
   vault kv put secret/myapp db_user="myuser" db_password="mypassword"
   ```

This uses the default **KV secrets engine** mounted at `secret/`. In the API, this is exposed at:

```text
/v1/secret/data/myapp
```

---

## 11. Simple Node.js Example to Read Credentials from Vault

This is a very simple Node.js script that:

1. Reads `VAULT_ADDR` and `VAULT_TOKEN` from environment variables.
2. Calls the Vault HTTP API.
3. Reads the secret at `secret/myapp`.
4. Prints `db_user` and `db_password`.

### 11.1. Prerequisites

- **Node.js 18+** (so we can use the built-in `fetch`).
- A working Vault dev server as described above.
- The secret `secret/myapp` created with `db_user` and `db_password`.

### 11.2. Set Environment Variables on Windows

#### PowerShell

```powershell
$env:VAULT_ADDR = "http://127.0.0.1:8200"
$env:VAULT_TOKEN = "root"
```

#### CMD

```cmd
set VAULT_ADDR=http://127.0.0.1:8200
set VAULT_TOKEN=root
```

You can verify:

```bash
echo %VAULT_ADDR%
echo %VAULT_TOKEN%
```

(or `$env:VAULT_ADDR` in PowerShell).

### 11.3. `package.json`

In a directory for your Node.js app (for example `C:\data\dev\check\node-app`):

```bash
npm init -y
```

If you want to be explicit that you are using ES modules (optional):

```jsonc
// package.json
{
  "name": "vault-node-simple-example",
  "version": "1.0.0",
  "main": "index.js",
  "type": "module",
  "scripts": {
    "start": "node index.js"
  }
}
```

### 11.4. `index.js` – Simple Vault Client

Create `index.js` with the following content:

```js
// index.js
// Very simple example of reading credentials from Vault (dev mode) using Node.js

const VAULT_ADDR = process.env.VAULT_ADDR || "http://127.0.0.1:8200";
const VAULT_TOKEN = process.env.VAULT_TOKEN || "root"; // dev default

// Secret path we created earlier: vault kv put secret/myapp ...
// For KV v2 the HTTP API path is /v1/secret/data/<name>
const SECRET_PATH = "secret/data/myapp";

async function readSecret() {
  try {
    const url = `${VAULT_ADDR}/v1/${SECRET_PATH}`;

    const response = await fetch(url, {
      method: "GET",
      headers: {
        "X-Vault-Token": VAULT_TOKEN,
      },
    });

    if (!response.ok) {
      const text = await response.text();
      throw new Error(
        `Vault API error: ${response.status} ${response.statusText} - ${text}`
      );
    }

    const json = await response.json();

    // For KV v2, data is nested as: { data: { data: { ... }, metadata: { ... } } }
    const secretData = json.data && json.data.data;

    if (!secretData) {
      throw new Error("No secret data found in Vault response");
    }

    const dbUser = secretData.db_user;
    const dbPassword = secretData.db_password;

    console.log("Successfully read secret from Vault:");
    console.log("db_user:", dbUser);
    console.log("db_password:", dbPassword);
  } catch (err) {
    console.error("Error reading secret from Vault:", err.message);
    process.exit(1);
  }
}

readSecret();
```

Notes:

- We call `GET /v1/secret/data/myapp`, which matches the **KV v2** API layout.
- The script assumes the **dev root token** is `root`.

### 11.5. Running the Node.js Example

1. Make sure the Vault dev container is running:

   ```bash
   cd C:\data\dev\check\vault
   docker compose up -d
   ```

2. Export environment variables for Node.js (PowerShell):

   ```powershell
   cd C:\data\dev\check\node-app
   $env:VAULT_ADDR = "http://127.0.0.1:8200"
   $env:VAULT_TOKEN = "root"
   ```

3. Run the script:

   ```bash
   node index.js
   ```

If everything is configured correctly, you should see output like:

```text
Successfully read secret from Vault:
db_user: myuser
db_password: mypassword
```

---

## 12. Summary

- You are running **Vault in dev mode** using a simple `docker-compose.yml` on Windows.
- You created a **test secret** (`secret/myapp`) with `db_user` and `db_password`.
- You used a minimal **Node.js script** to call the Vault HTTP API and read those credentials.

From here, you can:

- Replace the hard-coded secret path with environment variables.
- Use other auth methods (AppRole, Kubernetes, etc.) instead of the root token.
- Switch from dev mode to a more realistic setup (file or Raft storage, TLS, persistent data).

## References
- [HashiCorp Vault Tutorial for Beginners - What, Why and How](https://www.youtube.com/watch?v=klyAhaklGNU)
- [HashiCorp Vault Explained in 180 seconds](https://www.youtube.com/watch?v=nG8fCdWkLzc)
