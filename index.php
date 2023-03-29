<?php
session_start();
$jsonString = file_get_contents("./asset/data/shoes.json");
$arrDataShoes = json_decode($jsonString, true);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./asset/css/bootstrap4.css">
    <link href="./asset/css/iconfontawesome/css/all.min.css" rel="stylesheet">
    <link href="./asset/css/style.css" rel="stylesheet">


    <!-- <script src="./asset/js/boostrap4.min.js"></script> -->
    <script src="./asset/js/jquery-3.2.1.slim.min.js"></script>
    <title>G_SNEAKER</title>
</head>

<body>
    <div class="app ">
        <div class="container">
            <div class="row mt-5">
                <div class="col col-lg-1 col-md-12 col-sm-12 col-xs-12"></div>
                <div class="col col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <div class="app_main our_product">

                        <div class="app_header out_product_header">Out Product</div>
                        <div class="app_content out_product_content">
                            <!-- Product item -->
                            <?php
                            foreach ($arrDataShoes['shoes'] as $key => $value) {
                                $dataValue = json_encode($value);
                                // echo $dataValue;
                            ?>
                                <div class="app_content_item mt-3">
                                    <div class="app_content_item_image" style="background-color: <?php echo $value['color'] ?>">
                                        <img src="<?php echo $value['image'] ?>">
                                    </div>
                                    <div class="app_content_item_title"><?php echo $value['name'] ?></div>
                                    <div class="app_content_item_description"><?php echo $value['description'] ?></div>
                                    <div class="app_content_item_footer">
                                        <div class="app_content_item_footer_price">$<?php echo $value['price'] ?></div>
                                        <div class="">
                                            <div>
                                                <div id="app_content_item_<?php echo $value['id'] ?>" class="app_content_item_footer_add_cart" onclick='handleAddToCart(<?php echo $dataValue ?>)'>ADD TO CART</div>

                                                <div id="app_content_item_checked<?php echo $value['id'] ?>" class="app_content_item_footer_add_checked non_active">
                                                    <i class="fas fa-check"></i>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                </div>
                            <?php
                            }

                            ?>
                        </div>


                    </div>
                </div>
                <div class="col col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
                <div class="col col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <div class="app_main your_cart">
                        <div class="app_header your_cart_header">
                            Your cart
                            <span class="your_cart_header_price"></span>
                        </div>
                        <div class="app_content your_cart_content">
                            <!-- Product order -->
                        </div>
                        <script>

                        </script>
                    </div>

                </div>
                <div class="col col-lg-1 col-md-12 col-sm-12 col-xs-12 "></div>
            </div>

        </div>
    </div>
    </div>
</body>
<script>
    const USDollar = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    var arrCartItem = [];
    var totalPrice = 0;
    const renderCart = () => {
        const checkCartItem = localStorage.getItem('cartItems');
        if (checkCartItem !== null) {
            const arrCartItemParse = JSON.parse(checkCartItem);
            if (arrCartItemParse.length > 0) {
                document.querySelector('.your_cart_content').innerHTML = '';
                var totalPrice = 0;
                arrCartItemParse.forEach((item, index) => {
                    totalPrice += item.price * item.amount;
                    var btnAddToCartCheck = document.querySelector(`#app_content_item_` + item.id)
                    var btnAddToCartChecked = document.querySelector(`#app_content_item_checked` + item.id);
                    btnAddToCartCheck.classList.add('non_active');
                    btnAddToCartChecked.classList.remove('non_active');
                    const html = `
                                <div class="your_cart_item mt-3 css_animation " id="your_cart_${item.id}">
                                        
                                           <div class="your_cart_item_left">
                                    <div class="your_cart_item_left_image" style="background-color:${item.color}">
                                        <img src="${item.image}">
                                    </div>

                                </div>  
                                        <div class="your_cart_item_right">
                                    <div class="your_cart_item_title">${item.name}</div>
                                    <div class="your_cart_item_price">${"$" +item.price}</div>
                                    <div class="your_cart_item_action">
                                        <div class="your_cart_item_action_count">
                                            <div class="your_cart_item_action_btn" onclick='decreaseAmount(${item.id})'>-</div>
                                            <div class="your_cart_item_action_number">${item.amount}</div>
                                            <div class="your_cart_item_action_btn" onclick='increaseAmount(${item.id})'>+</div>
                                        </div>
                                        <div class="your_cart_item_action_remove" onclick='handleRemoveCart(${item.id})' >
                                            <img  src="./asset/image/trash.png">
                                        </div>
                                    </div>

                                </div>
                                         
                                </div>`
                    document.querySelector('.your_cart_content').innerHTML += html;
                    document.querySelector('.your_cart_header_price').innerHTML = USDollar.format(totalPrice);

                })
            } else {
                const html = `<div>Your cart is empty.</div>`

                document.querySelector('.your_cart_content').innerHTML = html;
                document.querySelector('.your_cart_header_price').innerHTML = USDollar.format(0);
            }


        } else {
            const html = `<div>Your cart is empty.</div>`

            document.querySelector('.your_cart_content').innerHTML = html;
            document.querySelector('.your_cart_header_price').innerHTML = USDollar.format(0);
        }
    }
    renderCart();

    const handleAddToCart = (data = []) => {
        var btnAddToCart = document.querySelector(`#app_content_item_` + data.id)
        var btnAddToCartChecked = document.querySelector(`#app_content_item_checked` + data.id)

        const cartItem = localStorage.getItem('cartItems');
        if (cartItem != null) {
            const cartItemParse = JSON.parse(cartItem);
            cartItemParse.push({
                id: data.id,
                amount: 1,
                image: data.image,
                name: data.name,
                description: data.description,
                price: data.price,
                color: data.color
            })
            const setJson01 = JSON.stringify(cartItemParse)
            localStorage.setItem('cartItems', setJson01);
        } else {
            arrCartItem.push({
                id: data.id,
                amount: 1,
                image: data.image,
                name: data.name,
                description: data.description,
                price: data.price,
                color: data.color
            });
            const setJson = JSON.stringify(arrCartItem)
            localStorage.setItem('cartItems', setJson);
        }
        btnAddToCart.classList.add('non_active');
        btnAddToCartChecked.classList.remove('non_active');

        renderCart();

        var cssCart = document.querySelector(`#your_cart_` + data.id);
        cssCart.classList.add('test1');


    }

    const handleRemoveCart = (id) => {
        const checkCartItem = localStorage.getItem('cartItems');
        var btnAddToCart = document.querySelector(`#app_content_item_` + id)
        var btnAddToCartChecked = document.querySelector(`#app_content_item_checked` + id)
        if (checkCartItem !== null) {
            const arrCartItemParse = JSON.parse(checkCartItem);
            // arrCartItemParse.include(id)
            arrCartItemParse.forEach((item) => {
                if (item.id === id) {
                    arrCartItemParse.splice(arrCartItemParse.indexOf(item), 1);
                    localStorage.setItem('cartItems', JSON.stringify(arrCartItemParse));
                }
            })
            btnAddToCartChecked.classList.add('non_active');
            btnAddToCart.classList.remove('non_active');
            var cssCart = document.querySelector(`#your_cart_` + id);
            cssCart.classList.add('test2');
        }

        renderCart();


    }

    const increaseAmount = (id) => {
        const checkCartItem = localStorage.getItem('cartItems');
        var btnAddToCart = document.querySelector(`#app_content_item_` + id)
        var btnAddToCartChecked = document.querySelector(`#app_content_item_checked` + id)
        if (checkCartItem !== null) {
            const arrCartItemParse = JSON.parse(checkCartItem);
            // arrCartItemParse.include(id)
            arrCartItemParse.forEach((item, index) => {
                if (item.id === id) {
                    var tempCart = arrCartItemParse[index];
                    arrCartItemParse[index].amount += 1
                    localStorage.setItem('cartItems', JSON.stringify(arrCartItemParse));
                }
            })
            renderCart();
        }
    }
    const decreaseAmount = (id) => {
        const checkCartItem = localStorage.getItem('cartItems');
        var btnAddToCart = document.querySelector(`#app_content_item_` + id)
        var btnAddToCartChecked = document.querySelector(`#app_content_item_checked` + id)
        if (checkCartItem !== null) {
            const arrCartItemParse = JSON.parse(checkCartItem);
            // arrCartItemParse.include(id)
            arrCartItemParse.forEach((item, index) => {
                if (item.id === id) {
                    var tempCart = arrCartItemParse[index];

                    if (arrCartItemParse[index].amount > 1) {
                        arrCartItemParse[index].amount -= 1
                    } else {
                        arrCartItemParse.splice(arrCartItemParse.indexOf(item), 1);
                        btnAddToCartChecked.classList.add('non_active');
                        btnAddToCart.classList.remove('non_active');
                    }
                    localStorage.setItem('cartItems', JSON.stringify(arrCartItemParse));
                }
            })
            renderCart();
        }
    }
</script>

</html>