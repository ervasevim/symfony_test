# PATH CHALLENGE

### POSTMAN COLLECTION
---
https://documenter.getpostman.com/view/9260602/UVeCRTx7


### Requirements
---

- PHP ^7.4.20|^8.0
- Composer
- Symfony 5.4


### Installation
---

```
git clone https://github.com/ervasevim/path_challenge.git
cd path_challenge
composer update
```

### Configuration database
---

```
- php bin/console doctrine:database:create
- php bin/console make:migration 
- php bin/console doctrine:migrations:migrate
```

### Configuration
---

```
- php bin/console lexik:jwt:generate-keypair
```