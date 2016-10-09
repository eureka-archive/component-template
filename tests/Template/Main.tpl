{% use Eureka\Component\Template: Template, Block %}<!DOCTYPE>
<html>
<head>
    <title>Eureka Home</title>
</head>
<body>
    {% partial: Template/Menu, $menu %}

    <h1>News</h1>

    {% block: javascript %}
    <script>
    function log(my_var) {
        console.log(my_var);
    }
    </script>
    {% endblock %}

    {# Display last news #}
    {% foreach($listNews as $news): %}<h2>{{@$news->title;}}</h2>{% endforeach; %}

    {% partial: Template/Footer %}


    {% getblock: javascript %}

</body>
</html>