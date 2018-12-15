## Instalação

No composer.json de sua aplicação, cole o código abaixo na chave "repositories"

```
{
    "repositories" : [
        {
            "type": "package",
            "package": {
                "name": "praxis-github/i10-manutencao-api-sdk",
                "version": "dev-master",
                "source": {
                    "url": "https://github.com/praxis-github/i10-manutencao-api-sdk",
                    "type": "git",
                    "reference": "master"
                },
                "autoload": {
                    "classmap": ["src/I10ManutencaoApiSdk.php"]
                }
            }
        }
    ],
    "require": {
        "praxis-github/i10-manutencao-api-sdk": "dev-master"        
    }
}

```