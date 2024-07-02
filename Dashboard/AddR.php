<header>
    <h1>Admin:</h1>
    <h1>Blood Recipients Details</h1>
        
</header>
<form method="post">
    <input type="text" name="Full_Name" id="Full_Name" placeholder="Eg. Fraizer Jethro G. Vargas">
    <br>
    <input type="text" name="Age" id="Age" placeholder="eg. 20">
    <br>
    <input type="date" name="Birth_Date" id="Birth_Date">
    <br>
    <select class="form-control" name="Blood_type" id="Blood_type">
    <option value="" disabled selected>Select Blood Type</option>
                <option value='A-'>A-</option>
                <option value='A+'>A+</option>
                <option value='B-'>B-</option>
                <option value='B+'>B+</option>
                <option value='O-'>O-</option>
                <option value='O+'>O+</option>
                <option value='AB-'>AB-</option>
                <option value='AB+'>AB+</option>
        </select>
    <br>
    <select class="form-control" name="Gender" id="Gender">
    <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                 <option value="Female">Female</option>
                <option value="Others">Others</option>
        </select>
    <br>
    <br>
    <input type="submit" name="Submit2-box" value="Donate">
</form>

<?php
    include "../Database/db_recipients.php"
    ?>

<br>
<a href = '../Design/Doctype.php'> click here to go back</a>