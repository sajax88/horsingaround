<div class="container">
    <div class="row">
        <div class="col-md-1 offset-md-11">
            {%- if current_user -%}
            <span>{{ current_user['full_name'] }}</span>
            <a class="btn btn-secondary" href="{{ url('auth/logout') }}">Logout</a>
            {%- endif -%}

        </div>
    </div>
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>&copy; Phalcon</p>
    </footer>
</div>
