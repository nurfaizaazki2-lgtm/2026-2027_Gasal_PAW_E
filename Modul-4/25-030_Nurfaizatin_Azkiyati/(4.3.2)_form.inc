<form name="myForm" action="processData.php" method="POST">

    <div class="field">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" size="31"
        value="<?php echo $_POST['surname'] ?? ''; ?>">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" size="31"
        value="<?php echo $_POST['email'] ?? ''; ?>">
    </div>

    <div class="field">
        <label for="passwd">Password</label>
        <input type="password" id="passwd" name="passwd" size="31"
        value="<?php echo $_POST['passwd'] ?? ''; ?>">
    </div>

    <div class="field">
        <label for="address">Street Address</label>
        <textarea id="address" name="address" rows="3" cols="30"><?php echo $_POST['address'] ?? ''; ?></textarea>
    </div>

    <div class="field">
        <label for="state">State</label>
        <select id="state" name="state">
            <option value="-1">[chose your state]</option>
            <option value="1">Australian Capital Territory</option>
            <option value="2">Queensland</option>
            <option value="3">Victoria</option>
            <option value="4">New South Wales</option>
            <option value="5">Tasmania</option>
            <option value="6">South Australia</option>
            <option value="7">Western Australia</option>
            <option value="8">Northern Territory</option>
        </select>
    </div>

    <input type="hidden" name="country" value="Australia">

    <div class="field">
        <label>Gender</label>
        <input type="radio" name="sex" value="male"> Male
        <input type="radio" name="sex" value="female"> Female
    </div>

    <div class="field">
        <label for="vegetarian">Vegetarian?</label>
        <input type="checkbox" id="vegetarian" name="vegetarian" value="yes">
    </div>

    <div class="field">
        <input type="submit" name="submit" value="Submit">
        <input type="reset" name="reset" value="Reset">
    </div>

</form>