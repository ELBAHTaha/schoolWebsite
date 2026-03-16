<h2>Bienvenue {{ $user->name }},</h2>
<p>Votre compte sur la plateforme JEFAL Prive a ete cree avec succes.</p>
<p><strong>Nom :</strong> {{ $user->name }}</p>
<p><strong>Email :</strong> {{ $user->email }}</p>
<p><strong>Mot de passe :</strong> {{ $plainPassword }}</p>
<p><strong>URL de connexion :</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
<p>Conservez ces informations en lieu sur.</p>
