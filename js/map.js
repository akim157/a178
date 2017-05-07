ymaps.ready(init);

function init () {
    var myMap = new ymaps.Map('map', {
            center: [59.865981,30.355874],
            zoom: 15
        }, {
            searchControlProvider: 'yandex#search'
        }),
        objectManager = new ymaps.ObjectManager({
            // ����� ����� ������ ����������������, ���������� �����.
            clusterize: true,
            // ObjectManager ��������� �� �� �����, ��� � �������������.
            gridSize: 32,
            clusterDisableClickZoom: true
        });

    // ����� ������ ����� ��������� �������� � ���������,
    // ��������� � �������� ���������� ObjectManager.
    objectManager.objects.options.set('preset', 'islands#greenDotIcon');
    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    myMap.geoObjects.add(objectManager);

    $.ajax({
        url: "js/data.json"
    }).done(function(data) {
        objectManager.add(data);
    });

}