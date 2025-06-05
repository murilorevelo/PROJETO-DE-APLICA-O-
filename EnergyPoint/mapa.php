<?php
include 'db.php';

// Obtem os pontos do banco de dados
$pontos = listar_pontos(); // já retorna lat/lng

include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pontos de Coleta com Rota</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main class="secao">
        <div class="container">
            <h1>Onde Carregar? Veja sua Rota!</h1>

            <!-- Barra de busca para origem e destino -->
            <div class="busca-simples">
                <div class="campo-busca">
                    <input id="origem" type="text" placeholder="Digite a origem (endereço ou cidade)">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="campo-busca">
                    <input id="destino" type="text" placeholder="Digite o destino (endereço ou cidade)">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>

            <!-- Botão Traçar Rota -->
            <button onclick="tracarRota()" class="botao botao-verde botao-grande botao-central">
                <i class="fas fa-route"></i> Calcular Rota
            </button>

            <!-- Mapa -->
            <div id="map"></div>

            <!-- Botão Voltar -->
            <a href="pontos.php" class="botao botao-verde botao-grande botao-voltar">
                <i class="fas fa-arrow-left"></i> Voltar aos Pontos
            </a>
        </div>
    </main>

    <script>
        // Solicita permissão de localização assim que o script carregar
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function() {},
                function() {}
            );
        }

        var map, directionsService, directionsRenderer;
        var autocompleteOrigem, autocompleteDestino;
        const pontos = <?php echo json_encode($pontos); ?>;
        var waypoints = []; // Array global para as paradas

        function marcarMinhaLocalizacao() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const minhaPosicao = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Centraliza o mapa na sua localização
                    map.setCenter(minhaPosicao);

                    // Adiciona um marker para sua localização
                    new google.maps.Marker({
                        position: minhaPosicao,
                        map: map,
                        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                        title: 'Minha localização'
                    });
                }, function(error) {
                    alert('Não foi possível obter sua localização.');
                });
            } else {
                alert('Geolocalização não suportada pelo navegador.');
            }
        }

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();

            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: -23.55052, lng: -46.633308 }, // São Paulo
                zoom: 10
            });

            directionsRenderer.setMap(map);

            // Marcar localização atual
            marcarMinhaLocalizacao();

            // Mostrar pontos do banco
            pontos.forEach(ponto => {
                if (ponto.lat && ponto.lng) {
                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(ponto.lat), lng: parseFloat(ponto.lng) },
                        map: map,
                        title: ponto.nome
                    });

                    const info = new google.maps.InfoWindow({
                        content: `
                            <div style="font-family: Arial, sans-serif; min-width:220px;">
                                <strong style="font-size:16px;">${ponto.nome}</strong><br>
                                <span style="color:#555;">CEP: ${ponto.cep}</span><br>
                                <span style="color:#555;">Número: ${ponto.numero || 'Não informado'}</span><br>
                                <span style="color:#007bff; font-style:italic;">Horário: ${ponto.horario_funcionamento}</span><br>
                                <div style="margin-top:8px;">
                                    <button style="background:#28a745;color:#fff;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;margin-right:5px;" onclick="gerarRotaAte(${ponto.lat}, ${ponto.lng})">Rota até aqui</button>
                                    <button style="background:#007bff;color:#fff;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;" onclick="adicionarParadaComFechamento(${ponto.lat}, ${ponto.lng}, window._ultimoInfoWindow)">Adicionar parada</button>
                                </div>
                            </div>
                        `
                    });
                    marker.addListener('click', () => {
                        if (window._ultimoInfoWindow) window._ultimoInfoWindow.close();
                        info.open(map, marker);
                        window._ultimoInfoWindow = info;
                    });
                }
            });

            // Carrega os pontos de carregamento elétrico
            carregarPontosDeCarga();

            // Autocomplete
            initAutocomplete();
        }

        function initAutocomplete() {
            autocompleteOrigem = new google.maps.places.Autocomplete(
                document.getElementById('origem'),
                { types: ['geocode'] }
            );

            autocompleteDestino = new google.maps.places.Autocomplete(
                document.getElementById('destino'),
                { types: ['geocode'] }
            );
        }

        function tracarRota() {
            var origem = document.getElementById('origem').value;
            var destino = document.getElementById('destino').value;

            if (!origem || !destino) {
                alert('Por favor, preencha origem e destino.');
                return;
            }

            var request = {
                origin: origem,
                destination: destino,
                waypoints: waypoints, // Inclui as paradas
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    alert('Erro ao traçar rota: ' + status);
                }
            });
        }

        function gerarRotaAte(lat, lng) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const origem = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    const destino = { lat: lat, lng: lng };

                    const request = {
                        origin: origem,
                        destination: destino,
                        travelMode: 'DRIVING'
                    };

                    directionsService.route(request, function(result, status) {
                        if (status === 'OK') {
                            directionsRenderer.setDirections(result);
                        } else {
                            alert('Erro ao gerar rota: ' + status);
                        }
                    });
                }, function(error) {
                    alert('Erro ao obter sua localização.');
                });
            } else {
                alert('Geolocalização não suportada.');
            }
        }

        function adicionarParada(lat, lng) {
            waypoints.push({
                location: { lat: parseFloat(lat), lng: parseFloat(lng) },
                stopover: true
            });
            alert('Parada adicionada à rota!');
            tracarRota(); // Atualiza a rota automaticamente
        }

        function adicionarParadaComFechamento(lat, lng, infoWindow) {
            waypoints.push({
                location: { lat: parseFloat(lat), lng: parseFloat(lng) },
                stopover: true
            });
            tracarRota(); // Atualiza a rota automaticamente
            // Fecha o InfoWindow após 1 segundo
            setTimeout(() => {
                if (infoWindow) infoWindow.close();
            }, 1000);
        }

        function carregarPontosDeCarga() {
            const apiKey = "<?php echo getenv('OPENCHARGEMAP_API_KEY'); ?>";
            const apiUrl = "https://api.openchargemap.io/v3/poi/?" +
                "output=json" +
                "&countrycode=BR" +
                "&latitude=-23.55052" +
                "&longitude=-46.633308" +
                "&distance=1000" +
                "&distanceunit=KM" +
                "&key=" + apiKey;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    data.forEach(estacao => {
                        const lat = estacao.AddressInfo.Latitude;
                        const lng = estacao.AddressInfo.Longitude;
                        const nome = estacao.AddressInfo.Title;

                        const conectores = estacao.Connections?.map(c => c.ConnectionType?.Title).join(', ') || 'Desconhecido';

                        const marker = new google.maps.Marker({
                            position: { lat: lat, lng: lng },
                            map: map,
                            icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                            title: nome
                        });

                        // Estilização dos botões e conteúdo
                        const info = new google.maps.InfoWindow({
                            content: `
                                <div style="font-family: Arial; min-width:220px;">
                                    <strong style="font-size:16px;">${nome}</strong><br>
                                    <span style="color:#555;">${estacao.AddressInfo.AddressLine1 || ''}</span><br>
                                    <em style="color:#007bff;">Conectores: ${conectores}</em><br>
                                    <div style="margin-top:8px;">
                                        <button style="background:#28a745;color:#fff;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;margin-right:5px;" onclick="gerarRotaAte(${lat}, ${lng})">Rota até aqui</button>
                                        <button style="background:#007bff;color:#fff;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;" onclick="adicionarParadaComFechamento(${lat}, ${lng}, window._ultimoInfoWindow)">Adicionar parada</button>
                                    </div>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            if (window._ultimoInfoWindow) window._ultimoInfoWindow.close();
                            info.open(map, marker);
                            window._ultimoInfoWindow = info;
                        });
                    });
                })
                .catch(error => {
                    console.error("Erro ao carregar pontos de carga:", error);
                });
        }
    </script>

    <script async defer
         src="https://maps.googleapis.com/maps/api/js?key=<?php echo getenv('Maps_API_KEY'); ?>&libraries=places&callback=initMap">
    </script>
</body>
</html>

<?php include 'footer.php'; ?>