## N8N

From NPM: 
```shell
npm install n8n -g
# or: n8n

n8n start
```

From Docker: 
```shell
docker run -it --rm \
  --name n8n \
  -p 5678:5678 \
  -v ~/.n8n:/home/node/.n8n \
  docker.n8n.io/n8nio/n8n
```

## Run 
- docker-compose up
- [LocalHost: N8N](http://localhost:5678)
- [LocalHost: Prometheus](http://localhost:9090) 

![](./rsc/screenshot-trial.jpg)

![](./rsc/credentials-1.jpg)

![](./rsc/credentials.jpg)

![](./rsc/workflow.jpg)

## References
- [NPM N8N](https://www.npmjs.com/package/n8n)
- [GitHub N8N](https://github.com/n8n-io/n8n)
- [Templates](./doc/templates.md)
- [Workflows](./doc/templates.md)
- [Templates List](https://n8n.io/workflows/?integrations=OpenAI)
- [Docker Installation](https://docs.n8n.io/hosting/installation/docker/)
- [Task runners#](https://docs.n8n.io/hosting/configuration/task-runners/)