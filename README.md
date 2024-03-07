![banner](https://banners.beyondco.de/The%20Rack.png?theme=light&packageManager=&packageName=https%3A%2F%2Fgithub.com%2Fsherwinchia%2Ftherack&pattern=rain&style=style_1&description=Laravel+7+Ecommerce+Website&md=0&showWatermark=0&fontSize=125px&images=shopping-cart&widths=250&heights=250)

## The Rack - Ecommerce Website

### Features

Guest
1. View Product
2. Register
3. Add product to cart

Customer
1. Login
2. Add product to cart
3. Checkout (No payment gateway implemented, all checkout status=PAID)
4. View purchase history

Admin 
1. Login to admin panel
2. CRUD product
3. CRUD size
4. Manage order
5. View user
6. Notes

### Installation
1. Clone the repository using the command "git clone [link]"
2. Create database in MySql
3. Configure the .env file accordingly
4. Run command 
```
$composer install
$php artisan migrate
$php artisan db:seed
$php artisan serve
$php artisan storage:link
```

### Built With
* Bootstrap- CSS framework
* JQuery- Javascript framework
* Laravel - PHP framework
* MySql- Databse

### Demo
https://www.youtube.com/watch?v=9WpcCnBOa8Q

### Note
If you find this repository useful, don't forget to star the repository. Credit not required but much appreciated! 

### Token of appreciation
[Saweria](https://saweria.co/sherwinchia) or 
[Paypal](https://www.paypal.me/sherwinchia)



### Agregar

*** Done
Las imagenes de storage no se ven ( php artisan storage:link )

*** To Do

integrar pixel de facebook.

integrar mercadopago (tener en cuenta la url HTTPS DE RETORNO)

ver de crear las ordenes con todos los datos para el posible CPA

ENV PARA MP MERCADOPAGO_ACCESS_TOKEN

ver categorias , sub categorias 

armar el modelo de diseño mobile igual

armar el modelo de diseño pc igual

En el Registro colocar telefono opcional

Hay link de confirmacion en registro? 

Recatpcha en registro

servidor de email en .env

crear buscador en el header pero que no sea suggestion. (?)

email-decode.min.js agregar este jafascript

variantes de producto (stock)

(stock de las variantes debe ser el total de stock del product)

ver el listado de productos el (cargar mas productos)

ver temas de posicionamiento. (idenfiticar titles , etc etc)

plugin de facebookde comentarios en productos.

cupones marketing (?)

slug de prductos para url friendly laravel

preload de tiendanube en el header del site 

url de og:image y og:secure para encabezados de meta (facebook)

ver facebook-domain-verification cuando se ponga en linea


### FUERA DE SCOPE

integrar oca (complejo fuera de scope)
