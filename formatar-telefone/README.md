# API em PHP
Essa solução foi criada com ChatGPT, e funciona da seguinte forma:

Primeiramente, precisamos subir esse código em um servidor, para que seja possível acessar online.

Após isso, é necessário realizar uma solicitação via HTTP, com um JSON no formato abaixo:

```
{
  "telefone": "4712345678"
}
```

Ele verifica se está no padrão internacional e com um 9 adicional, se não estiver, corrige e retorna o correto.
