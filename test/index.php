<?php
include('../src/jajjimento.php');

$jaji = new Jajjimento();

if(isset($_POST['username']))
{
    
    
    $rules = $jaji->add('username')->length(3, 12)->req()
                  ->add('password')->length(6, 30)->req()
                  ->add('confirm') ->equals('password')
                  ->add('age')->type('range')->min(1)->max(99)
                  ->add('url')->url()->req()
                  ->save();
    
    $jaji->source($_POST)
         ->loadCheck($rules);
}
?>

<?php if(isset($_POST['username'])) { ?>

<p>Rules:</p>

<table border="1">
    <thead>
        <tr>
          <th>Field</th>
          <th>Type</th>
          <th>Min</th>
          <th>Max</th>
          <th>Required</th>
          <th>dateFormat</th>
          <th>inside</th>
          <th>urlNot</th>
          <th>trim</th>
          <th>format</th>
          <th>target</th>
        </tr>
   </thead>
    <tbody>
        <?php foreach($rules as $rule) { ?>
        <tr>
            <td><?= $rule['field']; ?></td>
            <td><?= $rule['type']; ?></td>
            <td><?= $rule['min']; ?></td>
            <td><?= $rule['max']; ?></td>
            <td><?= $rule['required']; ?></td>
            <td><?= $rule['dateFormat']; ?></td>
            <td><?= $rule['inside']; ?></td>
            <td><?= $rule['urlNot']; ?></td>
            <td><?= $rule['trim']; ?></td>
            <td><?= $rule['format']; ?></td>
            <td><?= $rule['target']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<p>&nbsp;</p>

<p>Errors:</p>

<table border="1">
    <thead>
        <tr>
          <th>Field</th>
          <th>Raw Field</th>
          <th>Type</th>
          <th>Min</th>
          <th>Max</th>
          <th>Required</th>
          <th>dateFormat</th>
          <th>inside</th>
          <th>urlNot</th>
          <th>trim</th>
          <th>format</th>
          <th>target</th>
          <th>reason</th>
          <th>time</th>
        </tr>
   </thead>
    <tbody>
        <?php foreach($jaji->errors as $error) { ?>
        <tr>
            <td><?= $error['field']; ?></td>
            <td><?= $error['rawField']; ?></td>
            <td><?= $error['type']; ?></td>
            <td><?= $error['min']; ?></td>
            <td><?= $error['max']; ?></td>
            <td><?= $error['required']; ?></td>
            <td><?= $error['dateFormat']; ?></td>
            <td><?= $error['inside']; ?></td>
            <td><?= $error['urlNot']; ?></td>
            <td><?= $error['trim']; ?></td>
            <td><?= $error['format']; ?></td>
            <td><?= $error['target']; ?></td>
            <td><?= $error['reason']; ?></td>
            <td><?= $error['time']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<p>&nbsp;</p>

<a href="index.php">Try again</a>

<?php } else { ?>

<form action="index.php" method="POST">
  <p>Username <strong>min length: 3, max length: 12, required</strong></p>
  <input type="text" name="username">
  <p>Password <strong>min length: 6, max length: 30, required</strong></p>
  <input type="text" name="password">
  <p>Confirm <strong>required, same as Password</strong></p>
  <input type="text" name="confirm">
  <p>Age <strong>min: 1, max: 99</strong></p>
  <input type="text" name="age">
  <p>URL <strong>required, type: url</strong></p>
  <input type="text" name="url">
  <p><button>Submit</button></p>
  <?= $jaji->insertCrumb(); ?>
</form>

<?php } ?>