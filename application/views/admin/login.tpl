{if empty($message)}
   <p> Please log in. </p>
{else}
   <p> {$message} </p>
{/if}

<form method="post">
User: <input type="text" name="user" />
Password: <input type="password" name="pass" />
<input type="submit" value="Login" />
</form>
