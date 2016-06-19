{* Eureka\Component\Template: Template, Block *}<!DOCTYPE>
<html>
<head>
    <title>Eureka Home</title>
</head>
<body>
    {% partial: Template/Menu, $oMenu %}

    <h1>News</h1>

    {% block: javascript %}
    <script>
    function log(my_var) {
        console.log(my_var);
    }
    </script>
    {% endblock %}

    {# Display last news #}
    {{foreach($aNews as $oNews):}}<h2>{{@$oNews->title;}}</h2>{{endforeach;}}

    {% partial: Template/Footer %}


    {% getblock: javascript %}

</body>
</html>