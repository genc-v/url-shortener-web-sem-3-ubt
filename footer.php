<?php
// navbar.php


function renderFooter()
{

    ?>
    <footer>
        <div class="footer-top">
            <div class="logo-div">
                <a href="index.php"><img src="assets/images/png/logo.png" class="logo" alt=""></a>
            </div>

            <div class="linkContainer">
                <a class="footerLink" href="index.php">Home</a>
                <a class="footerLink" href="search.php">Search</a>
                <a class="footerLink" href="url.php">Urls</a>
            </div>

            <div class="icon-container">
                <!-- Instagram -->
                <a href="https://www.instagram.com" target="_blank" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.332 3.608 1.307.975.975 1.245 2.242 1.307 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.332 2.633-1.307 3.608-.975.975-2.242 1.245-3.608 1.307-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.332-3.608-1.307-.975-.975-1.245-2.242-1.307-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.849c.062-1.366.332-2.633 1.307-3.608C4.514 2.495 5.781 2.225 7.147 2.163 8.412 2.104 8.792 2.163 12 2.163zm0 1.838c-3.135 0-3.482.012-4.71.068-1.012.048-1.785.22-2.395.83-.61.61-.782 1.383-.83 2.395-.056 1.228-.068 1.575-.068 4.71s.012 3.482.068 4.71c.048 1.012.22 1.785.83 2.395.61.61 1.383.782 2.395.83 1.228.056 1.575.068 4.71.068s3.482-.012 4.71-.068c1.012-.048 1.785-.22 2.395-.83.61-.61.782-1.383.83-2.395.056-1.228.068-1.575.068-4.71s-.012-3.482-.068-4.71c-.048-1.012-.22-1.785-.83-2.395-.61-.61-1.383-.782-2.395-.83-1.228-.056-1.575-.068-4.71-.068zm0 3.459a4.362 4.362 0 1 1 0 8.725 4.362 4.362 0 0 1 0-8.725zm0 1.838a2.524 2.524 0 1 0 0 5.048 2.524 2.524 0 0 0 0-5.048zm4.839-3.419a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1z" />
                    </svg>
                </a>

                <!-- Gmail -->
                <a href="mailto:your-email@gmail.com" target="_blank" aria-label="Gmail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12.713l11.99-7.146C23.95 5.228 23.523 5 23 5H1C.478 5 .051 5.228.01 5.567L12 12.713zM12 14.644L.01 7.506v11.347C.051 19.772.478 20 1 20h22c.523 0 .95-.228.99-.567V7.506L12 14.644z" />
                    </svg>
                </a>

                <!-- LinkedIn -->
                <a href="https://www.linkedin.com" target="_blank" aria-label="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20.447 20.452H17.31v-4.914c0-1.17-.022-2.675-1.632-2.675-1.634 0-1.883 1.274-1.883 2.59v4.999h-3.135v-10.17h3.006v1.387h.042c.419-.795 1.44-1.633 2.963-1.633 3.174 0 3.763 2.087 3.763 4.798v5.618zM5.337 9.048c-1.01 0-1.826-.818-1.826-1.828 0-1.01.818-1.828 1.826-1.828 1.01 0 1.826.818 1.826 1.828 0 1.01-.818 1.828-1.826 1.828zm1.55 11.404H3.786v-10.17h3.1v10.17zM22.225 0H1.771C.792 0 0 .775 0 1.728v20.543C0 23.226.792 24 1.771 24h20.451C23.204 24 24 23.226 24 22.271V1.728C24 .775 23.204 0 22.225 0z" />
                    </svg>
                </a>

                <!-- GitHub -->
                <a href="https://github.com" target="_blank" aria-label="GitHub">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.477 2 2 6.485 2 12.017c0 4.43 2.867 8.191 6.839 9.514.5.09.682-.217.682-.482 0-.237-.009-.868-.014-1.703-2.782.605-3.37-1.343-3.37-1.343-.454-1.155-1.11-1.462-1.11-1.462-.908-.62.07-.607.07-.607 1.003.07 1.531 1.032 1.531 1.032.893 1.531 2.341 1.089 2.911.832.091-.648.349-1.089.635-1.339-2.22-.254-4.555-1.113-4.555-4.951 0-1.093.39-1.987 1.031-2.688-.103-.254-.447-1.275.098-2.656 0 0 .84-.269 2.75 1.025A9.548 9.548 0 0 1 12 6.845c.85.004 1.704.115 2.502.337 1.907-1.293 2.746-1.025 2.746-1.025.548 1.381.203 2.402.1 2.656.643.701 1.03 1.595 1.03 2.688 0 3.847-2.339 4.694-4.565 4.943.358.308.678.92.678 1.854 0 1.338-.012 2.418-.012 2.745 0 .268.18.578.688.48A10.019 10.019 0 0 0 22 12.017C22 6.485 17.523 2 12 2z" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <p> &copy; 2024 Bytely. All rights reserved.</p>
        </div>


    </footer>



    <style>
        footer {
            height: 150px;
            margin-top: 100px;
            background: rgba(238, 238, 238, 0.7);
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-around;
        }

        .footer-top {
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-top>* {
            width: 30%;
        }

        .linkContainer {
            display: flex;
            justify-content: space-around;

        }

        .icon-container {
            display: flex;
            gap: 50px;
            justify-content: end;
        }

        .logo {
            width: 100px;
        }

        svg {
            scale: 1.25;
        }

        .footerLink {
            position: relative;
            font-weight: 600;
            color: black;
            opacity: 0.8;
            margin: 0 20px;
            font-size: 1.75rem;
        }

        .footerLink::before {
            content: "";
            position: absolute;
            width: 0%;
            height: 2px;
            translate: 0 22px;
            background: var(--blue);
            transition: 0.3s;
        }

        .footerLink:hover {
            color: var(--blue);
            transition: 0.3s;
        }


        .footerLink:hover::before {
            width: 100%;
        }

        @media (max-width:757px) {

            .logo-div {
                display: flex;
                justify-content: center;
            }

            footer {
                height: 210px;
                justify-content: start;
                gap: 20px;
            }


            .footer-top>* {
                width: 100%;
                justify-content: center;
            }

            .footer-top {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>

    <?php
}
