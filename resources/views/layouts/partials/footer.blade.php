<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-text">
                    {{ date('Y') }} © <strong>{{ config('app.name', 'JoinMe') }}</strong>. All rights reserved.
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-links text-md-end">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ route('conversations.index') }}">Conversations</a>
                    <a href="{{ route('profile.edit') }}">Profile</a>
                </div>
            </div>
        </div>
    </div>
</footer>
