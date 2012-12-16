<h1>message list</h1>
{% for key,value in results %}
    name: {{ value.name }}<br />
    mail: {{ value.mail }}<br />
    message: {{ value.message }}<br /> <br /> 
{% endfor %}    
