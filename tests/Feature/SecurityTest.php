<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_redirection(): void{
        $response = $this->get('/account');
        $response->assertRedirect("/auth");//Le server devra nous redirigier vers la page d'authentification

        $response = $this->get('/checkout');
        $response->assertRedirect("/auth");
    }

    public function test_unauthorized_request(): void{
        $response = $this->post('/updateInfo');
        $response->assertUnauthorized();//Le server devra nous dire que nous ne somme pas autorisé

        $response = $this->post('/ajouterFavoris');
        $response->assertUnauthorized();

        $response = $this->post('/supprimerFavoris');
        $response->assertUnauthorized();

        $response = $this->post('/adresse/ajout');
        $response->assertUnauthorized();

        $response = $this->post('/adresse/supprimer');
        $response->assertUnauthorized();

        $response = $this->post('/panier/ajout');
        $response->assertUnauthorized();

        $response = $this->post('/panier/supprimer');
        $response->assertUnauthorized();

        $response = $this->get('/getNumberInPanier');
        $response->assertUnauthorized();

        $response = $this->post('/checkout/valider');
        $response->assertUnauthorized();
    }
}
