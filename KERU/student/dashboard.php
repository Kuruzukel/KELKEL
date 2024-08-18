
<style>

.box1, .box2 {
    flex: 1;
    border-top-right-radius: 10px;
    text-align: center;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    margin: 0;
    border: 3px solid #a0a0a0;
    border-top: 10px solid #ffee00;
    box-sizing: border-box;
}

.box1 {
    border-top-left-radius: 0;
}

.box2 {
    border-top-right-radius: 0;
    border-top-left-radius: 10px;
}

.flex-container {
    display: flex;
    flex-wrap: wrap;
    height: auto;
    overflow: hidden;
    width: 100%;
}

@media screen and (max-width: 768px) {
    .flex-container {
        flex-direction: column;
    }

    .box1, .box2 {
        width: 100%;
        height: auto;
    }
}
</style>
<div class="flex-container">
<div class="box1">
<br>
Academic Year: 2024-2025 1st semester

<?php echo $_SESSION['student_id'];?>
</div>
<br><br><br><br>
<div class="box2">
<br>
Student Information
</div>
</div>