<?php

?>

<main class="container profile-page">
    <section class="profile-hero">
        <div class="hello-text">
            <h1>HEJ IGEN</h1>
            <h2>NAVN</h2>
        </div>

        <div class="profile-intro">
            <div>
                <h3>MIN PROFIL</h3>
                <p>Her kan du se og redigere dine personlige oplysninger</p>
            </div>

            <div class="profile-image-wrapper">
                <img src="/assets/img/profile_picture_test.png" alt="Profilbillede">
                <button class="camera-btn" type="button"><img src="/assets/img/camera_icon.png"
                        alt="Kamera ikon"></button>
            </div>
        </div>
    </section>

    <section class="profile-admin-container">
        <h4>REDIGER PROFIL</h4>

        <form method="POST">
            <input type="text" name="name" placeholder="Fornavn" required>

            <input type="text" name="last_name" placeholder="Efternavn" required>

            <input type="text" name="last_name" placeholder="Studieretning" required>

            <input type="text" name="last_name" placeholder="Semester" required>

            <input type="email" name="email" placeholder="Studiemail" required>

            <input type="password" name="password" placeholder="Adgangskode" required>

            <button class="btn btn-primary" type="submit">GEM ÆNDRINGER</button>
        </form>
    </section>
</main>

<style>
    .profile-page {
        display: grid;
        grid-template-columns: 100px 1fr;
        column-gap: var(--gap-xs);
        margin-top: var(--gap-sm);
    }

    .profile-hero {
        display: contents;
    }

    .hello-text {
        grid-row: 1 / span 2;
        display: flex;
        align-items: start;

        h1 {
            font-family: var(--font-header);
            font-size: var(--font-mobil-h1);
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            margin-left: -20px;
        }

        h2 {
            font-family: var(--font-header);
            font-size: var(--font-mobil-h2);
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }
    }

    .profile-intro {
        grid-column: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--gap-sm);

        h3 {
            font-family: var(--font-header);
            font-size: var(--font-mobil-h3);
            margin-bottom: var(--gap-sm);
        }

        p {
            font-family: var(--font-text);
            font-size: var(--font-mobil-text);
            max-width: 150px;
        }
    }

    .profile-image-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        flex-shrink: 0;
    }

    .profile-image-wrapper img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .camera-btn {
        position: absolute;
        right: 0px;
        bottom: 0px;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: var(--color-light-yellow);
        font-size: 37px;
        cursor: pointer;

        img {
            right: 0px;
            bottom: 0px;
            width: 30px;
            height: 30px;
        }
    }

    .profile-admin-container {
        grid-column: 2;
        margin: 30px auto;

        h4 {
            font-family: var(--font-header);
            font-size: var(--font-mobil-text);
            margin-bottom: var(--gap-xs);
        }
    }

    input {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        color: var(--font-color-black);
        width: 100%;
        border: none;
        border-bottom: 1px solid var(--font-color-black);
        padding: var(--gap-sm) 0;
        margin-bottom: var(--gap-md);
        outline: none;
    }

    input::placeholder {
        color: var(--font-color-black);
    }

    @media (min-width: 768px) {
        .profile-page {
            display: grid;
            grid-template-columns: 460px 1fr;
            column-gap: var(--gap-md);
            margin-top: var(--gap-md);
            max-width: 1450px;
        }

        .profile-hero {
            display: contents;
        }

        .hello-text {
            grid-row: 1 / span 2;
            display: flex;
            align-items: start;

            h1 {
                font-family: var(--font-header);
                font-size: var(--font-desktop-h1-special);
                writing-mode: vertical-rl;
                transform: rotate(180deg);
            }

            h2 {
                font-family: var(--font-header);
                font-size: var(--font-desktop-h2);
                writing-mode: vertical-rl;
                transform: rotate(180deg);
            }
        }

        .profile-intro {
            grid-column: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--gap-md);

            h3 {
                font-family: var(--font-header);
                font-size: var(--font-desktop-h3);
                margin-bottom: var(--gap-md);
            }

            p {
                font-family: var(--font-text);
                font-size: var(--font-desktop-text-small);
                max-width: 90%;
            }
        }

        .profile-image-wrapper {
            width: 200px;
            height: 200px;
            margin-right: var(--gap-md);
        }

        .camera-btn {
            width: 58px;
            height: 58px;
            right: 0;
            bottom: 0;
            font-size: 54px;

            img {
                width: 45px;
                height: 45px;
            }
        }

        .profile-admin-container {
            grid-column: 2;
            width: 420px;
            margin: 50px 0 0 0;
            margin-bottom: var(--gap-md);

            h4 {
                font-family: var(--font-header);
                font-size: var(--font-desktop-text-small);
                margin-bottom: var(--gap-sm);
            }
        }

        input {
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            color: var(--font-color-black);
            width: 100%;
            border: none;
            border-bottom: 2px solid var(--font-color-black);
            padding: var(--gap-sm) 0 var(--gap-sm);
            margin-bottom: var(--gap-md);
            outline: none;
        }

        input::placeholder {
            color: var(--font-color-black);
        }
    }
    
</style>