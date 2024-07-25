<div class="row">
    <div class="col-md-6">
        <div class="page-header">
            <h2>Log In</h2>
        </div>
        <form action="{{ url('auth/login') }}" role="form" method="post">
            <fieldset>
                <div class="form-group">
                    {{ form.label('email', ['class': 'control-label']) }}
                    <div class="controls">
                        {{ form.render('email', ['class': 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ form.label('password', ['class': 'control-label']) }}
                    <div class="controls">
                        {{ form.render('password', ['class': 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ tag.inputSubmit('Login', null, ['class': 'btn btn-primary btn-large', 'id': null, 'name': null, 'value': 'Login']) }}
                </div>
            </fieldset>
        </form>
    </div>
</div>
