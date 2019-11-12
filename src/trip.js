/*
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

const $ = require('jquery');
require('mapbox-gl');
require('@mapbox/mapbox-gl-geocoder');

import mapboxgl from 'mapbox-gl';
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';


function registerTripDetail() {
    if (!$('#milestone_map').length) return;

    function update_url_milestone(milestone_id) {
        const url = new URL(window.location.href);
        const search_params = new URLSearchParams(url.search);
        search_params.set('milestone', milestone_id);
        url.search = search_params.toString();
        history.pushState(null, document.title, url.toString());
    }

    function getQueryMilestoneId() {
        const url = new URL(window.location.href);
        const search_params = new URLSearchParams(url.search);
        return search_params.get('milestone');
    }

    function getGeojson(container) {
        return {
            "type": "FeatureCollection",
            "features": $(container).find('.milestone-meta')
                .toArray()
                .map(function (milestoneMeta) {
                    const meta = $(milestoneMeta);
                    return {
                        "type": "Feature",
                        "properties": {
                            "id": meta.data('id'),
                            "elementId": meta.attr('id'),
                            "img": meta.data('img'),
                            "date": meta.data('date'),
                            'locationName': meta.data('location-name')
                        },
                        "geometry": {
                            "type": "Point",
                            "coordinates": [
                                meta.data('location-lat'),
                                meta.data('location-lon')
                            ]
                        }
                    };
                })
        };
    }

    function initializeMap(initCoords) {
        return new mapboxgl.Map({
            container: 'milestone_map',
            style: 'mapbox://styles/mapbox/outdoors-v11',
            center: initCoords,
            zoom: 12,
            minZoom: 1,
            maxZoom: 20,
            pitch: 45,
            bearing: -17.6,
            antialias: true
        });
    }

    function isMilestoneVisible(elem, container) {
        const containerLeft = container.scrollLeft();
        const containerRight = containerLeft + container.width();

        const elementLeft = $(elem).offset().left + 20;
        const elementRight = elementLeft + $(elem).width() - 20;

        return ((elementRight <= containerRight) && (elementLeft >= containerLeft));
    }

    function scrollToMilestone(id) {
        const container = $('#milestone_container');
        const target = $('#milestone-meta-' + id);
        const offset = (target.offset().left - container.offset().left);
        container.stop().animate({'scrollLeft': offset}, 900, 'swing');
    }

    function featureToMarker(feature) {
        const el = document.createElement('div');
        el.className = 'marker';
        el.id = 'marker-' + feature.properties.id;
        el.style.backgroundImage = 'url("' + feature.properties.img + '")';

        el.addEventListener('click', function (e) {
            e.preventDefault();
            scrollToMilestone(feature.properties.id);
        }.bind(feature));
        return el;
    }

    function setActiveMarker(milestone_id, lat, lon, map) {
        $('.marker').each(function () {
            $(this).removeClass('active');
        });
        $('#marker-' + milestone_id).addClass('active');
        map.flyTo({
            center: [lat, lon],
            zoom: 11,
            bearing: 0,
            speed: 1.5, // make the flying slow
            curve: 2, // change the speed at which it zooms out
            easing: function (t) {
                return t;
            }
        });
    }

    function addRouteLayer(map, lineCoords, markerColor) {
        map.addLayer({
            "id": "route",
            "type": "line",
            "source": {
                "type": "geojson",
                "data": {
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "type": "LineString",
                        "coordinates": lineCoords
                    }
                }
            },
            "layout": {
                "line-join": "round",
                "line-cap": "round"
            },
            "paint": {
                "line-color": markerColor,
                "line-width": 2
            }
        });
    }

    function addBuildingLayer(map) {
        const layers = map.getStyle().layers;

        let labelLayerId;
        for (let i = 0; i < layers.length; i++) {
            if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                labelLayerId = layers[i].id;
                break;
            }
        }

        map.addLayer({
            'id': '3d-buildings',
            'source': 'composite',
            'source-layer': 'building',
            'filter': ['==', 'extrude', 'true'],
            'type': 'fill-extrusion',
            'minzoom': 15,
            'paint': {
                'fill-extrusion-color': '#aaa',
                'fill-extrusion-height': [
                    "interpolate", ["linear"], ["zoom"],
                    15, 0,
                    15.05, ["get", "height"]
                ],
                'fill-extrusion-base': [
                    "interpolate", ["linear"], ["zoom"],
                    15, 0,
                    15.05, ["get", "min_height"]
                ],
                'fill-extrusion-opacity': .6
            }
        }, labelLayerId);
    }

    $('#milestones', function () {

        const milestonesWrapper = $(this);
        const milestoneContainer = $('#milestone_container');

        const geojson = getGeojson(milestoneContainer);

        const initCoords = geojson.features.length
            ? geojson.features[0].geometry.coordinates
            : [-63.29223632812499, -18.28151823530889];

        mapboxgl.accessToken = framework.mapbox_api_key;

        const map = initializeMap(initCoords);

        const bounds = new mapboxgl.LngLatBounds();
        const lineCoords = [];


        geojson.features.forEach(function (feature) {
            bounds.extend(feature.geometry.coordinates);
            lineCoords.push(feature.geometry.coordinates);
            new mapboxgl.Marker(featureToMarker(feature))
                .setLngLat(feature.geometry.coordinates)
                .addTo(map);
        });


        let markerColor = $('.marker').css('border-color');

        map.fitBounds(bounds, {padding: 50});

        milestonesWrapper.find('#show_all_btn').on('click', function (e) {
            e.preventDefault();
            map.fitBounds(bounds, {padding: 50});
        });

        map.on('load', function () {
            addRouteLayer(map, lineCoords, markerColor);
            addBuildingLayer(map);
        });

        let lastMilestone = null;
        milestoneContainer.on('scroll', function () {
            const metas = $(this).find('.milestone-meta').toArray();
            for (let meta of metas) {
                if (isMilestoneVisible(meta, milestoneContainer)) {
                    const metaEl = $(meta);
                    const milestoneId = metaEl.data('id');
                    if (lastMilestone === milestoneId) return;
                    lastMilestone = milestoneId;
                    const lat = metaEl.data('locationLat');
                    const lon = metaEl.data('locationLon');
                    setActiveMarker(milestoneId, lat, lon, map);
                    update_url_milestone(milestoneId);
                    return;
                }
            }
        });

        const query_milestone = getQueryMilestoneId();
        if (query_milestone) {
            scrollToMilestone(query_milestone);
        }

    });
}

function registerAdminMilestone() {

    if (!$('#milestone_settings').length) return;
    $('#milestone_settings', function () {
        const form = $(this);
        const location_name_field = form.find('#kar_travel_milestone_location_name');
        const location_lat_field = form.find('#kar_travel_milestone_location_lat');
        const location_lon_field = form.find('#kar_travel_milestone_location_lon');
        const search_input = form.find('.mapboxgl-ctrl-geocoder--input');

        let inital_lat = location_lat_field.val();
        let inital_lon = location_lon_field.val();
        let inital_name = location_name_field.val();

        if (!inital_lat) {
            inital_lat = 52.520008;
        }

        if (!inital_lon) {
            inital_lon = 13.404954;
        }

        mapboxgl.accessToken = framework.mapbox_api_key;
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [inital_lat, inital_lon],
            zoom: 12,
            minZoom: 1,
            maxZoom: 15,
            interactive: false,
            attributionControl: false
        });
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            language: 'de-DE',
            mapboxgl: mapboxgl
        });

        const initalMarker = new mapboxgl.Marker()
            .setLngLat([inital_lat, inital_lon])
            .addTo(map);

        form.find('#geocoder').append(geocoder.onAdd(map));

        geocoder.on('result', function (e) {
            initalMarker.remove();
            const result = e.result;
            const coords = result.center;
            const name = result.place_name;
            location_lat_field.val(coords[0]);
            location_lon_field.val(coords[1]);
            location_name_field.val(name);
        });

    });
}

$(document).ready(function ($) {
    'use strict';

    registerTripDetail();
    registerAdminMilestone();
});
