<div class="container">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Contatti</h3>
            <p>Email: info@example.com</p>
            <p>Tel: +39 123 456 789</p>
        </div>
        
        <div class="footer-section">
            <h3>Link Utili</h3>
            <ul>
                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ url('/terms') }}">Termini e Condizioni</a></li>
                <li><a href="{{ url('/cookie') }}">Cookie Policy</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
    </div>
</div> 