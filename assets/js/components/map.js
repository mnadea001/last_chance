'use strict';

import L from 'leaflet';
import 'devbridge-autocomplete';

// Pour une raison obscure, lorsque nous utilisons Webpack, nous devons redéfinir les icons
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

require('leaflet-easybutton');
require('@ansur/leaflet-pulse-icon');

class Map {
    init(mapId, center = [45.5, 2], zoom = 5) {
        this.map = L.map(mapId, { center: center, zoom: zoom });
        L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);
    }
}

export default Map;

_search() {
    let proprieties = ['name', 'housenumber', 'street', 'suburb', 'hamlet', 'town', 'city', 'state', 'country'];

    $('#address').autocomplete({
        serviceUrl: 'https://photon.komoot.de/api/',
        paramName: 'q',
        params: { lang: 'fr', limit: 5 },
        dataType: 'json',
        onSelect: (suggestion) => {
            let position = suggestion.data.geometry.coordinates;

            // À la selection, on ajoute un marqueur sur la carte et on la recentre
            L.marker([position[1], position[0]]).addTo(this.map);
            this.map.setView([position[1], position[0]], 12);
        },
        transformResult: (response) => {
            // On reformate la réponse de l'api afin de respecter le contrat du plugin
            return {
                suggestions: $.map(response.features, (dataItem) => {
                    return {
                        value: proprieties
                            .map((p) => { return dataItem.properties[p]; })
                            .filter((v) => { return !!v; }).
                            join(', '),
                        data: dataItem
                    };
                })
            };
        }
    });
}