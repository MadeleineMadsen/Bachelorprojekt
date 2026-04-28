<?php

?>

<main class="container">
    <h1>OPRET EVENT</h1>

    <section class="create-event-container">
        <form method="POST">
            <input type="text" name="titel" placeholder="Titel på event" required>

            <div class="form-row">
                <input type="date" name="date" placeholder="Dato" required>

                <input type="time" name="time" placeholder="Tid" required>
            </div>

            <input type="text" name="location" placeholder="Lokation" required>

            <label class="form-label" for="description">Beskrivelse</label>
            <textarea id="description" name="description" required></textarea>

            <label class="form-label">Upload billede</label>
            <div class="upload-box">
                <input type="file" name="image" accept="image/*">
                <div class="upload-icon"><img src="/assets/img/upload_picture.png" alt="Upload billede ikon"></div>
                <p>Træk og slip et billede her<br>eller klik for at vælge fil</p>
            </div>

            <div class="button-row">
                <button class="btn btn-primary" type="submit">OPRET EVENT</button>

                <button class="btn btn-secondary" type="reset">ANNULLER</button>
            </div>
        </form>
    </section>
</main>

<style>
    h1 {
        font-family: var(--font-header);
        font-size: var(--font-mobil-h1);
        width: 80%;
    }

    .create-event-container {
        width: 300px;
        margin: 30px auto;
    }

    input {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        color: var(--font-color-black);
        width: 100%;
        border: none;
        border-bottom: 1px solid var(--font-color-black);
        padding: var(--gap-sm) 0 var(--gap-sm);
        margin-bottom: var(--gap-sm);
        outline: none;
    }

    input::placeholder {
        color: var(--font-color-black);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--gap-sm);
    }

    .form-label {
        display: block;
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        margin-bottom: var(--gap-sm);
    }

    textarea {
        width: 100%;
        height: 170px;
        resize: none;
        border: 1px solid var(--font-color-black);
        margin-bottom: var(--gap-sm);
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        padding: var(--gap-sm);
        outline: none;
    }

    .upload-box {
        width: 100%;
        min-height: 130px;
        border: 2px dotted var(--font-color-black);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: var(--gap-xs);
        text-align: center;
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        cursor: pointer;
        margin-bottom: var(--gap-lg);
    }

    .upload-box {
        margin-bottom: var(--gap-md);

        input {
            display: none;
        }

        p {
            font-family: var(--font-text);
            font-size: var(--font-mobil-text);
            color: var(--font-color-black);
            margin-bottom: var(--gap-sm);
        }

    }

    .upload-icon img {
        width: 48px;
        height: auto;
        margin-top: var(--gap-xs)
    }

    .button-row {
        display: flex;
        gap: var(--gap-md);
        align-items: center;

        .btn {
            padding: 0;
        }
    }

    @media (min-width: 768px) {
        h1 {
            font-family: var(--font-header);
            font-size: var(--font-desktop-h1);
            width: 80%;
        }

        .create-event-container {
            width: 720px;
            margin: 70px auto;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--gap-md);
        }

        .form-label {
            display: block;
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            margin-bottom: var(--gap-sm);
        }

        textarea {
            width: 100%;
            height: 200px;
            resize: none;
            border: 1px solid var(--font-color-black);
            margin-bottom: var(--gap-md);
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            padding: var(--gap-sm);
            outline: none;
        }

        .upload-box {
            width: 100%;
            min-height: 180px;
            border: 2px dotted var(--font-color-black);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: var(--gap-xs);
            text-align: center;
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            cursor: pointer;
            margin-bottom: var(--gap-md);
        }

        .upload-box {
            margin-bottom: var(--gap-md);

            input {
                display: none;
            }

            p {
                font-family: var(--font-text);
                font-size: var(--font-desktop-text-small);
                color: var(--font-color-black);
                margin-bottom: var(--gap-md);
            }

        }

        .upload-icon img {
            width: 70px;
            height: auto;
            margin-top: var(--gap-md)
        }

        .button-row {
            display: flex;
            gap: var(--gap-md);
            align-items: center;

            .btn {
                padding: 0;
            }
        }
    }
</style>