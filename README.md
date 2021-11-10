# cashdro-simulator

Pequeño simulador de los webservices de las máquinas de CasDro.

Este pequeño script responde a las peticiones básicas para facilitar una primera fase de la integración básica con
las máquinas de CashDro.

Este script **no** quiere ser un simulador completo de la máquina.

Este script tiene una pequeña base de datos en SQLite para tener algo de memoria y poder controlar el estado de las operaciones.

Solo tiene dos errores posibles, user+pass es incorrecto y operación inexistente.

Este script no es de CashDro, me lo he creado yo para ayudarme con la integración.

## Historia

Trabajando en un software me toco integrarme con las máquinas de cashdro, el distribuidor me dejaba una para probar pero
con el problema que estaba a 600km de mi lugar de trabajo. Podia ir un compañero pero tendria que estar compartiendo
pantalla mientras yo trabajaba e iba probando.

Para ir allí con algo más funcional y aprovechar mejor nuestro rato con la máquina me cree este script para poder probar
de manera básica nuestra integración.

## Instalación

Este proyecto usa composer para las dependencias

## Datos

- Usuario: `admin`
- Contraseña: `password`

## Más información

[cashdro.com](https://cashdro.com)