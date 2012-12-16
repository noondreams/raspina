<form action="" method="post">
	name: <input name="data[name]"  type="text" value="{{ result.name }}"/><br />
	mail: <input name="data[mail]" type="text" value="{{ result.mail }}" /><br />
	message:<br />
	<textarea name="data[message]" rows="4" cols="30">{{ result.message }}</textarea><br />
    <input type="submit" name="send" value="Update Message" />
</form>
<br /><br />
{{ msg }}
