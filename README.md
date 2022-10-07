# sys-kdb-core

System Knowledge Database Core, based on KDM (Knowledge Discovery Metamodel;  ISO/IEC 19506)

This work is part of my master's degree thesis.


## Instructions

### To setup the DEV environment

```
docker build -t dev-sys-kdb-core .

PHP_INSTANCE=`docker run -d -v "$(pwd):/app"  dev-sys-kdb-core`
docker exec -it $PHP_INSTANCE bash


docker stop $PHP_INSTANCE
```