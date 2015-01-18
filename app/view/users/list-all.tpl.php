<h1><?=$title?></h1>

<p><a href="<?=$this->url->create('users/add/')?>">Lägg till användare</a></p>

<h2>Aktiva användare</h2>
<table class="all-users-list">
     <tr>
         <th class="all-users-list-th1">ID</th>
         <th class="all-users-list-th2">Användarnamn</th>
         <th class="all-users-list-th3">Namn</th>
         <th class="all-users-list-th4">Email</th>
         <th class="all-users-list-th5"> </th>
         <th class="all-users-list-th6"> </th>
    </tr>
<?php foreach ($activeUsers as $user) : ?>
<?php $props = $user->getProperties(); ?>
    <tr>
         <td><?=$props['id']?></td>
         <td><a href="<?=$this->url->create('users/id/'.$props['id'])?>"><?=$props['acronym']?></a></td>
         <td><?=$props['name']?></td>
         <td><?=$props['email']?></td>
         <td><a href="<?=$this->url->create('users/inactivate/'.$props['id'])?>">Inaktivera</a></td>
         <td><a href="<?=$this->url->create('users/soft-delete/'.$props['id'])?>">Flytta till papperskorgen</a></td>
    </tr> 
<?php endforeach; ?>
</table>

<h2>Inaktiva användare</h2>
<table class="all-users-list">
     <tr>
         <th class="all-users-list-th1">ID</th>
         <th class="all-users-list-th2">Användarnamn</th>
         <th class="all-users-list-th3">Namn</th>
         <th class="all-users-list-th4">Email</th>
         <th class="all-users-list-th5"> </th>
         <th class="all-users-list-th6"> </th>
    </tr>
<?php foreach ($inActiveUsers as $user) : ?>
<?php $props = $user->getProperties(); ?>
    <tr>
         <td><?=$props['id']?></td>
         <td><a href="<?=$this->url->create('users/id/'.$props['id'])?>"><?=$props['acronym']?></a></td>
         <td><?=$props['name']?></td>
         <td><?=$props['email']?></td>
         <td> </td>
         <td><a href="<?=$this->url->create('users/activate/'.$props['id'])?>">Aktivera</a></td>
    </tr> 
<?php endforeach; ?>
</table>

<h2>Papperskorgen</h2>
<table class="all-users-list">
     <tr>
         <th class="all-users-list-th1">ID</th>
         <th class="all-users-list-th2">Användarnamn</th>
         <th class="all-users-list-th3">Namn</th>
         <th class="all-users-list-th4">Email</th>
         <th class="all-users-list-th5"> </th>
         <th class="all-users-list-th6"> </th>
    </tr>

<?php foreach ($deletedUsers as $user) : ?>
<?php $props = $user->getProperties(); ?>
    <tr>
         <td><?=$props['id']?></td>
         <td><a href="<?=$this->url->create('users/id/'.$props['id'])?>"><?=$props['acronym']?></a></td>
         <td><?=$props['name']?></td>
         <td><?=$props['email']?></td>
         <td><a href="<?=$this->url->create('users/undo-delete/'.$props['id'])?>">Ångra</a></td>
         <td><a href="<?=$this->url->create('users/delete/'.$props['id'])?>">Radera</a></td>
    </tr> 
<?php endforeach; ?>
</table>
