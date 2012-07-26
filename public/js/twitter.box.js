$(document).ready(function() {
	new TWTR.Widget(
		{
		    id: 'twitterBox',
		    version: 2,
		    type: 'search',
		    search: '#phpscconf OR @PHP_SC',
		    interval: 10000,
		    title: 'PHPSC Conference',
		    subject: 'O que estão falando...',
		    width: '100%',
		    height: 159,
		    theme: {
		        shell: {
		            background: '#28779b',
		            color: '#ffffff'
		        },
		        tweets: {
		            background: '#ffffff',
		            color: '#444444',
		            links: '#28779b'
		        }
		    },
		    features: {
		        scrollbar: false,
		        loop: true,
		        live: true,
		        behavior: 'default'
		        }
		    }
	).render().start();
});