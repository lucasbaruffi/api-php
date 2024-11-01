# API em PHP
Essa solução foi criada com ChatGPT, e funciona da seguinte forma:

Primeiramente, precisamos subir esse código em um servidor, para que seja possível acessar online.

Após isso, é necessário realizar uma solicitação via HTTP, com um JSON no formato abaixo:

```
{
  "nome1": 30,
  "nome2": 40,
  "nome3": 30
}
```

Este JSON possui um nome e a probabilidade de retornar esse nome, então a função vai sortear um número e, baseado na probabilidade, retornará o nome.


Esta solução foi pensada para dividir Leads para SDRs, em que, por exemplo, uma SDR em treinamento receberia uma probabilidade menor de receber Leads.
