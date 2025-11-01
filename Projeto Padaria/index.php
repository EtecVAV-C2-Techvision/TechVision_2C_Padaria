<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Padaria Grano d’Oro</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="src/styles/header.css">
  <link rel="stylesheet" href="src/styles/footer.css">
  <link rel="stylesheet" href="src/styles/menu.css">
  <link rel="stylesheet" href="src/styles/testimonials.css">
  <link rel="stylesheet" href="src/styles/styles.css">
  <link rel="stylesheet" href="src/styles/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <header>
    <nav id="navbar">
      <i class="fa-solid fa-bread-slice" id="nav_logo" >grano</i>

      <ul id="nav_list">
        <li class="nav-item active">
            <a href="#home">Início</a>
        </li>
        <li class="nav-item">
            <a href="#menu">Cardápio</a>
        </li>
        <li class="nav-item">
            <a href="#testimonials">Avaliações</a>
        </li>
      </ul>
      <button class="btn-default"> <a href='clientes/compras.php'>
     Compras
    </a></button>
    <button id="mobile_btn">
      <i class="fa-solid fa-bars"> </i>
    </button>

    </nav>
   
    <div id="mobile_menu">
      <ul id="mobile_nav_list">
        <li class="nav-item">
            <a href="#home">Início</a>
        </li>
        <li class="nav-item">
            <a href="#menu">Cardápio</a>
        </li>
        <li class="nav-item">
            <a href="#testimonials ">Avaliações</a>
        </li>
      </ul>
      <button class="btn-default"> <a href='clientes/compras.php'>
     Compras
    </a></button>
    </div>

  </header>

  <main id="content">
    <section id="home">

      <div id="shape"></div>

      <div id="cta">

        <h1 class="title">
          O Sabor que vai até 
          <span>você!</span>
        </h1>

        <p class="description">A Grano D´Oro nasceu para unir tradição e sofisticação na arte da panificação. Com ingredientes selecionados e atenção aos detalhes,
             oferece produtos de alta qualidade em um ambiente elegante, proporcionando uma experiência única aos clientes.
        </p>

        <div id="cta_buttons">
         <a href="#menu" class="btn-default"> Ver Cardápio</a> 

        <a href="tel:+55555555555" id="phone_button">
        <button class="btn-default">
        <i class="fa-solid fa-phone"></i>
        </button>
        (51) 92342-3243
        </a>
        </div>

        <div class="social-media-buttons">
          <a href="https://wa.me/551123456789">
            <i class="fa-brands fa-whatsapp"></i>
          </a>

          <a href="">
            <i class="fa-brands fa-instagram"></i>
          </a>

          <a href="">
            <i class="fa-brands fa-facebook"></i>
          </a>

        </div>


      </div>

    <div id="banner">
      <img src="imagens/banner.png" alt="">

    </div>
    </section>

 <section id="menu">
            <h2 class="section-title">Cardápio</h2>
            <h3 class="section-subtitle">Nossos pratos especiais</h3>

            <div id="dishes">
                <div class="dish">
                    <div class="dish-heart">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <img src="imagens/variosPao.jpg" class="dish-image" alt="">

                    <h3 class="dish-title">
                        Pães Especiais
                    </h3>

                    <span class="dish-description">
                        Pães fresquinhos da maior qualidade e variedade!
                    </span>

                    <div class="dish-rate">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>(500+)</span>
                    </div>
                    <div class="dish-price">
                        <a href='clientes/compras.php'><button class="btn-default">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </button></a>
                    </div>
                </div>

                <div class="dish">
                    <div class="dish-heart">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <img src="imagens/bolochu.jpg" class="dish-image" alt="">

                    <h3 class="dish-title">
                        Bolos
                    </h3>

                    <span class="dish-description">
                        Bolos de Padaria, com diversos sabores e recheios.
                    </span>

                    <div class="dish-rate">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>(500+)</span>
                    </div>
                    <div class="dish-price">
                        <a href='clientes/compras.php'><button class="btn-default">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </button></a>
                    </div>
                </div>

                <div class="dish">
                    <div class="dish-heart">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <img src="imagens/paofrances.avif" class="dish-image" alt="">

                    <h3 class="dish-title">
                        Pão Francês
                    </h3>

                    <span class="dish-description">
                        Nosso clássico brasileiro com nosso toque especial.
                    </span>

                    <div class="dish-rate">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>(500+)</span>
                    </div>
                    <div class="dish-price">
                        <a href='clientes/compras.php'><button class="btn-default">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </button></a>
                    </div>
                </div>

                <div class="dish">
                    <div class="dish-heart">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <img src="imagens/croissant.avif" class="dish-image" alt="">

                    <h3 class="dish-title">
                        Croissants
                    </h3>

                    <span class="dish-description">
                        O amanteigado queridinho com um toque de Brasilidade.
                    </span>

                    <div class="dish-rate">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>(500+)</span>
                    </div>

                    <div class="dish-price">
                        <a href='clientes/compras.php'><button class="btn-default">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </button></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials">
            <img src="src/images/chef.png" id="testimonial_chef" alt="">

            <div id="testimonials_content">
                <h2 class="section-title">
                    Depoimentos
                </h2>
                <h3 class="section-subtitle">
                    O que os clientes falam sobre nós
                </h3>

                <div id="feedbacks">
                    <div class="feedback">
                        <img src="imagens/userwoman.jpg" class="feedback-avatar" alt="">

                        <div class="feedback-content">
                            <p>
                                Olívia
                                <span>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </span>
                            </p>
                            <p>
                                "Adoro a qualidade e o sabor dos pães daqui! Sempre fresquinhos e 
                                feitos com muito carinho."
                            </p>
                        </div>
                    </div>

                    <div class="feedback">
                        <img src="imagens/userman.jpg" class="feedback-avatar" alt="">

                        <div class="feedback-content">
                            <p>
                                Guilhermo
                                <span>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </span>
                            </p>
                            <p>
                                "Variedade incrível de produtos e tudo sempre muito saboroso.
                                 Recomendo de olhos fechados!"
                            </p>
                        </div>
                    </div>
                </div>

                <button class="btn-default">
                    Ver mais avaliações
                </button>
            </div>
        </section>
  </main>

  <footer>
    <img src="src/images/wave.svg" alt="">

    <div id="footer_items">
      <span id="copyright">
           &copy 2024 Grano D´Oro
      </span>

      <div class="social-media-buttons">
        <a href="https://wa.me/551123456789">
        <i class="fa-brands fa-whatsapp"></i>
        </a>

      <a href="">
        <i class="fa-brands fa-instagram"></i>
      </a>

      <a href="">
        <i class="fa-brands fa-facebook"></i>
      </a>
         </div>

        </div>

    </footer>
  
  <script src="src/javascript/script.js"></script>

</body>
</html>
