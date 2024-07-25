<ul>
    {% for person_request in person_requests %}
    <li>
        {{ person_request.full_name | e }}: {{ person_request.description }}
    </li>
    {% endfor %}
</ul>
