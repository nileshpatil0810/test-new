<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0 auto;
  max-width: 800px;
  padding: 0 20px;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
</style>
</head>
<body>
<div id="show_users">
  <?php foreach ($get_user_data as $key) { ?>
    <div class="form-group">
    <button type="submit" class="btn send-chat btn-success" id = "<?php echo $key->id; ?>" >"<?php echo $key->email; ?>"</button>
    </div>
  <?php }?>
</div>


<h2>Chat Messages</h2>


<div id= "get_chat">
  <div id = "fetch_data">

  </div>
<!-- <div class="container">
  <p>Hello. How are you today?</p>
  <span class="time-right">11:00</span>
</div>

<div class="container darker">
  <p>Hey! I'm fine. Thanks for asking!</p>
  <span class="time-left">11:01</span>
</div>

<div class="container">
  <p>Sweet! So, what do you wanna do today?</p>
  <span class="time-right">11:02</span>
</div>

<div class="container darker">
  <p>Nah, I dunno. Play soccer.. or learn more coding perhaps?</p>
  <span class="time-left">11:05</span>
</div> -->

<label class="control-label" for="title">Type Here:</label>
<textarea name="msg_body" class="form-control" data-error="Please enter message" id="msg_body" required></textarea>
<div class="help-block with-errors"></div>

<div class="form-group">
 <button type="submit" class="btn send-msg btn-success">Submit</button>
</div>
<input type = "hidden" name = "receiver_id" value = "" id = "receiver_id">
</div>
</body>
</html>