<?php use Framework\Classes\Lang; ?>

<h2><?= $subpageTitle ?></h2>

<form id="user-form" method="POST" data-form-data="<?= htmlspecialchars(json_encode($formData)) ?>">
    <div class="form-row">
        <div class="form-column">
            <label for="name"><?= Lang::get('app.form.name') ?>:</label>
            <input type="text" name="name" class="input-field">
        </div>
        <div class="form-column">
            <label for="username"><?= Lang::get('app.form.username') ?>:</label>
            <input type="text" name="username" class="input-field">
        </div>
        <div class="form-column">
            <label for="email"><?= Lang::get('app.form.email') ?>:</label>
            <input type="text" name="email" class="input-field">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="phone"><?= Lang::get('app.form.phone') ?>:</label>
            <input type="text" name="phone" class="input-field">
        </div>
        <div class="form-column">
            <label for="website"><?= Lang::get('app.form.website') ?>:</label>
            <input type="text" name="website" class="input-field">
        </div>
        <div class="form-column"></div>
    </div>

    <fieldset class="main-fieldset">
        <legend><?= Lang::get('app.form.address.title') ?></legend>

        <div class="form-row">
            <div class="form-column">
                <label for="address[street]"><?= Lang::get('app.form.address.street') ?>:</label>
                <input type="text" name="address[street]" class="input-field">
            </div>
            <div class="form-column">
                <label for="address[suite]"><?= Lang::get('app.form.address.suite') ?>:</label>
                <input type="text" name="address[suite]" class="input-field">
            </div>
            <div class="form-column">
                <label for="address[city]"><?= Lang::get('app.form.address.city') ?>:</label>
                <input type="text" name="address[city]" class="input-field">
            </div>
        </div>
        <div class="form-row">
            <div class="form-column">
                <label for="address[zipcode]"><?= Lang::get('app.form.address.zipcode') ?>:</label>
                <input type="text" name="address[zipcode]" class="input-field">
            </div>

            <fieldset class="inner-fieldset">
                <legend><?= Lang::get('app.form.address.geo.title') ?></legend>

                <div class="form-column">
                    <label for="address[geo][lat]"><?= Lang::get('app.form.address.geo.lat') ?>:</label>
                    <input type="number" step="any" name="address[geo][lat]" class="input-field">
                </div>
                <div class="form-column">
                    <label for="address[geo][lng]"><?= Lang::get('app.form.address.geo.lng') ?>:</label>
                    <input type="number" step="any" name="address[geo][lng]" class="input-field">
                </div>
            </fieldset>
        </div>
    </fieldset>
    <fieldset class="main-fieldset">
        <legend><?= Lang::get('app.form.company.title') ?></legend>

        <div class="form-row">
            <div class="form-column">
                <label for="company[name]"><?= Lang::get('app.form.company.name') ?>:</label>
                <input type="text" name="company[name]" class="input-field">
            </div>
            <div class="form-column">
                <label for="company[catchPhrase]"><?= Lang::get('app.form.company.catchPhrase') ?>:</label>
                <input type="text" name="company[catchPhrase]" class="input-field">
            </div>
            <div class="form-column">
                <label for="company[bs]"><?= Lang::get('app.form.company.bs') ?>:</label>
                <input type="text" name="company[bs]" class="input-field">
            </div>
        </div>
    </fieldset>

    <div class="form-buttons-container">
        <button class="form-button" onclick="cancelForm(event)"><?= Lang::get('app.form.cancel_button') ?></button>
        <button class="form-button" type="submit"><?= Lang::get('app.form.submit_button') ?></button>
    </div>
</form>
