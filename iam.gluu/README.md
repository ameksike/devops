## Gluu 
Open-source, self-hosted, SSO, and IAM solution with Docker support.
- Open-source IAM and SSO. 
- Gluu offers flexibility similar to Keycloak for on-prem SSO needs.
- Key Features: Supports OAuth, OpenID Connect, and SAML.

### Get Gluu’s Docker Repository
- Log in with your Docker ID or email address to push and pull images from Docker Hub. If you don't have a Docker ID, head over to https://hub.docker.com/ to create one. You can log in with your password or a Personal Access Token (PAT). Using a limited-scope PAT grants better security and is required for organizations using SSO. Learn more at https://docs.docker.com/go/access-tokens/

    ```shell
    docker login -u tonyks
    ```

- Clone Gluu’s Repository:
    ```bash
    git clone https://github.com/GluuFederation/gluu-docker.git
    cd gluu-docker
    docker-compose up
    ```

### Create the environment vars
- DOMAIN: This refers to the domain that the Gluu services will be hosted under. For local installations, this is usually a placeholder like localhost or a custom domain that points to your local machine (using /etc/hosts if needed). In a production setup, this would be a fully qualified domain name (FQDN) like example.com.

    For a local installation, you can set this to something like localhost or any custom domain (e.g., gluu.local).
    If you use a custom domain like gluu.local, ensure that it points to 127.0.0.1 in your /etc/hosts file.

- HOST_IP: This should be set to the IP address of the host machine where Docker is running. In most local setups, this would be your local machine's IP or 127.0.0.1.

- Example Configuration for Local Setup: For a local installation with Docker Compose, the environment variables could be set as follows:
    - DOMAIN: localhost or a custom domain like gluu.local (ensure you configure /etc/hosts accordingly).
    - HOST_IP: 127.0.0.1 (for local testing).

### Configuring the Windows Hosts File
To use gluu.local as the domain, you need to update the Windows hosts file to map the custom domain to your local machine’s IP (127.0.0.1).

- Steps to Update the Hosts File in Windows 11:
    - Open Notepad as an Administrator.
        ```
        C:\Windows\System32\drivers\etc\hosts
        ```

- Right-click on Notepad and select Run as Administrator.
    - In Notepad, open the following file:
        ```
        127.0.0.1 gluu.local
        ```
- Save the file: This tells Windows to route all traffic for gluu.local to 127.0.0.1, allowing Docker containers to use gluu.local as the domain for Gluu Federation services.

- Confirming Network Configuration: To ensure everything works, you should be able to access the Gluu services via the custom domain gluu.local. For example, after running Docker Compose, try accessing:
    - http://gluu.local:8080


## References 
- [Configure Gluu flex](https://docs.gluu.org/v5.1.3/install/docker-install/compose/#configure-gluu-flex)
- [Docker Installation](https://gluu.org/docs/gluu-server/4.0/installation-guide/install-docker/)
- [Github: Gluu Server Community Edition Containers](https://github.com/GluuFederation/community-edition-containers)
- [Gluu Federation & oxAuth](https://github.com/GluuFederation/oxAuth)
- [WSO2 API Manager & Gluu SSO (OIDC)](https://athiththan11.medium.com/wso2-api-manager-gluu-sso-oidc-8e0bc3f59b18)