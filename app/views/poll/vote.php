{% extends "base.html" %}
{% block content %}

<h1>Äänestä äänestyksessä {{poll.name}}</h1>

<form method="post" action="{{base_path}}/vote">

  <input name="poll_id" class="form-control" type="hidden" value="{{poll.id}}">
                   
  <div class="form-group">
    <label>Valintani</label>
    <select name="poll_option_id">
      {% for poll_option in poll_options %}
      <option value="{{poll_option.id}}">
	{{poll_option.name}}
	({{poll_option.description}})
      {% endfor %}
    </select>
  </div>
  
  <div class="form-group">
    <button type="submit" class="btn btn-primary">Äänestä</button>
  </div>

</form>

{% endblock %}
