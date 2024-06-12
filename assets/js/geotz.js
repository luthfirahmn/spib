(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.GeoTZ = f()}})(function(){var define,module,exports;return (function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var invariant_1 = require("@turf/invariant");
// http://en.wikipedia.org/wiki/Even%E2%80%93odd_rule
// modified from: https://github.com/substack/point-in-polygon/blob/master/index.js
// which was modified from http://www.ecse.rpi.edu/Homepages/wrf/Research/Short_Notes/pnpoly.html
/**
 * Takes a {@link Point} and a {@link Polygon} or {@link MultiPolygon} and determines if the point
 * resides inside the polygon. The polygon can be convex or concave. The function accounts for holes.
 *
 * @name booleanPointInPolygon
 * @param {Coord} point input point
 * @param {Feature<Polygon|MultiPolygon>} polygon input polygon or multipolygon
 * @param {Object} [options={}] Optional parameters
 * @param {boolean} [options.ignoreBoundary=false] True if polygon boundary should be ignored when determining if
 * the point is inside the polygon otherwise false.
 * @returns {boolean} `true` if the Point is inside the Polygon; `false` if the Point is not inside the Polygon
 * @example
 * var pt = turf.point([-77, 44]);
 * var poly = turf.polygon([[
 *   [-81, 41],
 *   [-81, 47],
 *   [-72, 47],
 *   [-72, 41],
 *   [-81, 41]
 * ]]);
 *
 * turf.booleanPointInPolygon(pt, poly);
 * //= true
 */
function booleanPointInPolygon(point, polygon, options) {
    if (options === void 0) { options = {}; }
    // validation
    if (!point) {
        throw new Error("point is required");
    }
    if (!polygon) {
        throw new Error("polygon is required");
    }
    var pt = invariant_1.getCoord(point);
    var geom = invariant_1.getGeom(polygon);
    var type = geom.type;
    var bbox = polygon.bbox;
    var polys = geom.coordinates;
    // Quick elimination if point is not inside bbox
    if (bbox && inBBox(pt, bbox) === false) {
        return false;
    }
    // normalize to multipolygon
    if (type === "Polygon") {
        polys = [polys];
    }
    var insidePoly = false;
    for (var i = 0; i < polys.length && !insidePoly; i++) {
        // check if it is in the outer ring first
        if (inRing(pt, polys[i][0], options.ignoreBoundary)) {
            var inHole = false;
            var k = 1;
            // check for the point in any of the holes
            while (k < polys[i].length && !inHole) {
                if (inRing(pt, polys[i][k], !options.ignoreBoundary)) {
                    inHole = true;
                }
                k++;
            }
            if (!inHole) {
                insidePoly = true;
            }
        }
    }
    return insidePoly;
}
exports.default = booleanPointInPolygon;
/**
 * inRing
 *
 * @private
 * @param {Array<number>} pt [x,y]
 * @param {Array<Array<number>>} ring [[x,y], [x,y],..]
 * @param {boolean} ignoreBoundary ignoreBoundary
 * @returns {boolean} inRing
 */
function inRing(pt, ring, ignoreBoundary) {
    var isInside = false;
    if (ring[0][0] === ring[ring.length - 1][0] &&
        ring[0][1] === ring[ring.length - 1][1]) {
        ring = ring.slice(0, ring.length - 1);
    }
    for (var i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        var xi = ring[i][0];
        var yi = ring[i][1];
        var xj = ring[j][0];
        var yj = ring[j][1];
        var onBoundary = pt[1] * (xi - xj) + yi * (xj - pt[0]) + yj * (pt[0] - xi) === 0 &&
            (xi - pt[0]) * (xj - pt[0]) <= 0 &&
            (yi - pt[1]) * (yj - pt[1]) <= 0;
        if (onBoundary) {
            return !ignoreBoundary;
        }
        var intersect = yi > pt[1] !== yj > pt[1] &&
            pt[0] < ((xj - xi) * (pt[1] - yi)) / (yj - yi) + xi;
        if (intersect) {
            isInside = !isInside;
        }
    }
    return isInside;
}
/**
 * inBBox
 *
 * @private
 * @param {Position} pt point [x,y]
 * @param {BBox} bbox BBox [west, south, east, north]
 * @returns {boolean} true/false if point is inside BBox
 */
function inBBox(pt, bbox) {
    return (bbox[0] <= pt[0] && bbox[1] <= pt[1] && bbox[2] >= pt[0] && bbox[3] >= pt[1]);
}

},{"@turf/invariant":3}],2:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
/**
 * @module helpers
 */
/**
 * Earth Radius used with the Harvesine formula and approximates using a spherical (non-ellipsoid) Earth.
 *
 * @memberof helpers
 * @type {number}
 */
exports.earthRadius = 6371008.8;
/**
 * Unit of measurement factors using a spherical (non-ellipsoid) earth radius.
 *
 * @memberof helpers
 * @type {Object}
 */
exports.factors = {
    centimeters: exports.earthRadius * 100,
    centimetres: exports.earthRadius * 100,
    degrees: exports.earthRadius / 111325,
    feet: exports.earthRadius * 3.28084,
    inches: exports.earthRadius * 39.37,
    kilometers: exports.earthRadius / 1000,
    kilometres: exports.earthRadius / 1000,
    meters: exports.earthRadius,
    metres: exports.earthRadius,
    miles: exports.earthRadius / 1609.344,
    millimeters: exports.earthRadius * 1000,
    millimetres: exports.earthRadius * 1000,
    nauticalmiles: exports.earthRadius / 1852,
    radians: 1,
    yards: exports.earthRadius * 1.0936,
};
/**
 * Units of measurement factors based on 1 meter.
 *
 * @memberof helpers
 * @type {Object}
 */
exports.unitsFactors = {
    centimeters: 100,
    centimetres: 100,
    degrees: 1 / 111325,
    feet: 3.28084,
    inches: 39.37,
    kilometers: 1 / 1000,
    kilometres: 1 / 1000,
    meters: 1,
    metres: 1,
    miles: 1 / 1609.344,
    millimeters: 1000,
    millimetres: 1000,
    nauticalmiles: 1 / 1852,
    radians: 1 / exports.earthRadius,
    yards: 1.0936133,
};
/**
 * Area of measurement factors based on 1 square meter.
 *
 * @memberof helpers
 * @type {Object}
 */
exports.areaFactors = {
    acres: 0.000247105,
    centimeters: 10000,
    centimetres: 10000,
    feet: 10.763910417,
    hectares: 0.0001,
    inches: 1550.003100006,
    kilometers: 0.000001,
    kilometres: 0.000001,
    meters: 1,
    metres: 1,
    miles: 3.86e-7,
    millimeters: 1000000,
    millimetres: 1000000,
    yards: 1.195990046,
};
/**
 * Wraps a GeoJSON {@link Geometry} in a GeoJSON {@link Feature}.
 *
 * @name feature
 * @param {Geometry} geometry input geometry
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature} a GeoJSON Feature
 * @example
 * var geometry = {
 *   "type": "Point",
 *   "coordinates": [110, 50]
 * };
 *
 * var feature = turf.feature(geometry);
 *
 * //=feature
 */
function feature(geom, properties, options) {
    if (options === void 0) { options = {}; }
    var feat = { type: "Feature" };
    if (options.id === 0 || options.id) {
        feat.id = options.id;
    }
    if (options.bbox) {
        feat.bbox = options.bbox;
    }
    feat.properties = properties || {};
    feat.geometry = geom;
    return feat;
}
exports.feature = feature;
/**
 * Creates a GeoJSON {@link Geometry} from a Geometry string type & coordinates.
 * For GeometryCollection type use `helpers.geometryCollection`
 *
 * @name geometry
 * @param {string} type Geometry Type
 * @param {Array<any>} coordinates Coordinates
 * @param {Object} [options={}] Optional Parameters
 * @returns {Geometry} a GeoJSON Geometry
 * @example
 * var type = "Point";
 * var coordinates = [110, 50];
 * var geometry = turf.geometry(type, coordinates);
 * // => geometry
 */
function geometry(type, coordinates, _options) {
    if (_options === void 0) { _options = {}; }
    switch (type) {
        case "Point":
            return point(coordinates).geometry;
        case "LineString":
            return lineString(coordinates).geometry;
        case "Polygon":
            return polygon(coordinates).geometry;
        case "MultiPoint":
            return multiPoint(coordinates).geometry;
        case "MultiLineString":
            return multiLineString(coordinates).geometry;
        case "MultiPolygon":
            return multiPolygon(coordinates).geometry;
        default:
            throw new Error(type + " is invalid");
    }
}
exports.geometry = geometry;
/**
 * Creates a {@link Point} {@link Feature} from a Position.
 *
 * @name point
 * @param {Array<number>} coordinates longitude, latitude position (each in decimal degrees)
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<Point>} a Point feature
 * @example
 * var point = turf.point([-75.343, 39.984]);
 *
 * //=point
 */
function point(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    if (!coordinates) {
        throw new Error("coordinates is required");
    }
    if (!Array.isArray(coordinates)) {
        throw new Error("coordinates must be an Array");
    }
    if (coordinates.length < 2) {
        throw new Error("coordinates must be at least 2 numbers long");
    }
    if (!isNumber(coordinates[0]) || !isNumber(coordinates[1])) {
        throw new Error("coordinates must contain numbers");
    }
    var geom = {
        type: "Point",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.point = point;
/**
 * Creates a {@link Point} {@link FeatureCollection} from an Array of Point coordinates.
 *
 * @name points
 * @param {Array<Array<number>>} coordinates an array of Points
 * @param {Object} [properties={}] Translate these properties to each Feature
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north]
 * associated with the FeatureCollection
 * @param {string|number} [options.id] Identifier associated with the FeatureCollection
 * @returns {FeatureCollection<Point>} Point Feature
 * @example
 * var points = turf.points([
 *   [-75, 39],
 *   [-80, 45],
 *   [-78, 50]
 * ]);
 *
 * //=points
 */
function points(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    return featureCollection(coordinates.map(function (coords) {
        return point(coords, properties);
    }), options);
}
exports.points = points;
/**
 * Creates a {@link Polygon} {@link Feature} from an Array of LinearRings.
 *
 * @name polygon
 * @param {Array<Array<Array<number>>>} coordinates an array of LinearRings
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<Polygon>} Polygon Feature
 * @example
 * var polygon = turf.polygon([[[-5, 52], [-4, 56], [-2, 51], [-7, 54], [-5, 52]]], { name: 'poly1' });
 *
 * //=polygon
 */
function polygon(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    for (var _i = 0, coordinates_1 = coordinates; _i < coordinates_1.length; _i++) {
        var ring = coordinates_1[_i];
        if (ring.length < 4) {
            throw new Error("Each LinearRing of a Polygon must have 4 or more Positions.");
        }
        for (var j = 0; j < ring[ring.length - 1].length; j++) {
            // Check if first point of Polygon contains two numbers
            if (ring[ring.length - 1][j] !== ring[0][j]) {
                throw new Error("First and last Position are not equivalent.");
            }
        }
    }
    var geom = {
        type: "Polygon",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.polygon = polygon;
/**
 * Creates a {@link Polygon} {@link FeatureCollection} from an Array of Polygon coordinates.
 *
 * @name polygons
 * @param {Array<Array<Array<Array<number>>>>} coordinates an array of Polygon coordinates
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the FeatureCollection
 * @returns {FeatureCollection<Polygon>} Polygon FeatureCollection
 * @example
 * var polygons = turf.polygons([
 *   [[[-5, 52], [-4, 56], [-2, 51], [-7, 54], [-5, 52]]],
 *   [[[-15, 42], [-14, 46], [-12, 41], [-17, 44], [-15, 42]]],
 * ]);
 *
 * //=polygons
 */
function polygons(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    return featureCollection(coordinates.map(function (coords) {
        return polygon(coords, properties);
    }), options);
}
exports.polygons = polygons;
/**
 * Creates a {@link LineString} {@link Feature} from an Array of Positions.
 *
 * @name lineString
 * @param {Array<Array<number>>} coordinates an array of Positions
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<LineString>} LineString Feature
 * @example
 * var linestring1 = turf.lineString([[-24, 63], [-23, 60], [-25, 65], [-20, 69]], {name: 'line 1'});
 * var linestring2 = turf.lineString([[-14, 43], [-13, 40], [-15, 45], [-10, 49]], {name: 'line 2'});
 *
 * //=linestring1
 * //=linestring2
 */
function lineString(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    if (coordinates.length < 2) {
        throw new Error("coordinates must be an array of two or more positions");
    }
    var geom = {
        type: "LineString",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.lineString = lineString;
/**
 * Creates a {@link LineString} {@link FeatureCollection} from an Array of LineString coordinates.
 *
 * @name lineStrings
 * @param {Array<Array<Array<number>>>} coordinates an array of LinearRings
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north]
 * associated with the FeatureCollection
 * @param {string|number} [options.id] Identifier associated with the FeatureCollection
 * @returns {FeatureCollection<LineString>} LineString FeatureCollection
 * @example
 * var linestrings = turf.lineStrings([
 *   [[-24, 63], [-23, 60], [-25, 65], [-20, 69]],
 *   [[-14, 43], [-13, 40], [-15, 45], [-10, 49]]
 * ]);
 *
 * //=linestrings
 */
function lineStrings(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    return featureCollection(coordinates.map(function (coords) {
        return lineString(coords, properties);
    }), options);
}
exports.lineStrings = lineStrings;
/**
 * Takes one or more {@link Feature|Features} and creates a {@link FeatureCollection}.
 *
 * @name featureCollection
 * @param {Feature[]} features input features
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {FeatureCollection} FeatureCollection of Features
 * @example
 * var locationA = turf.point([-75.343, 39.984], {name: 'Location A'});
 * var locationB = turf.point([-75.833, 39.284], {name: 'Location B'});
 * var locationC = turf.point([-75.534, 39.123], {name: 'Location C'});
 *
 * var collection = turf.featureCollection([
 *   locationA,
 *   locationB,
 *   locationC
 * ]);
 *
 * //=collection
 */
function featureCollection(features, options) {
    if (options === void 0) { options = {}; }
    var fc = { type: "FeatureCollection" };
    if (options.id) {
        fc.id = options.id;
    }
    if (options.bbox) {
        fc.bbox = options.bbox;
    }
    fc.features = features;
    return fc;
}
exports.featureCollection = featureCollection;
/**
 * Creates a {@link Feature<MultiLineString>} based on a
 * coordinate array. Properties can be added optionally.
 *
 * @name multiLineString
 * @param {Array<Array<Array<number>>>} coordinates an array of LineStrings
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<MultiLineString>} a MultiLineString feature
 * @throws {Error} if no coordinates are passed
 * @example
 * var multiLine = turf.multiLineString([[[0,0],[10,10]]]);
 *
 * //=multiLine
 */
function multiLineString(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    var geom = {
        type: "MultiLineString",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.multiLineString = multiLineString;
/**
 * Creates a {@link Feature<MultiPoint>} based on a
 * coordinate array. Properties can be added optionally.
 *
 * @name multiPoint
 * @param {Array<Array<number>>} coordinates an array of Positions
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<MultiPoint>} a MultiPoint feature
 * @throws {Error} if no coordinates are passed
 * @example
 * var multiPt = turf.multiPoint([[0,0],[10,10]]);
 *
 * //=multiPt
 */
function multiPoint(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    var geom = {
        type: "MultiPoint",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.multiPoint = multiPoint;
/**
 * Creates a {@link Feature<MultiPolygon>} based on a
 * coordinate array. Properties can be added optionally.
 *
 * @name multiPolygon
 * @param {Array<Array<Array<Array<number>>>>} coordinates an array of Polygons
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<MultiPolygon>} a multipolygon feature
 * @throws {Error} if no coordinates are passed
 * @example
 * var multiPoly = turf.multiPolygon([[[[0,0],[0,10],[10,10],[10,0],[0,0]]]]);
 *
 * //=multiPoly
 *
 */
function multiPolygon(coordinates, properties, options) {
    if (options === void 0) { options = {}; }
    var geom = {
        type: "MultiPolygon",
        coordinates: coordinates,
    };
    return feature(geom, properties, options);
}
exports.multiPolygon = multiPolygon;
/**
 * Creates a {@link Feature<GeometryCollection>} based on a
 * coordinate array. Properties can be added optionally.
 *
 * @name geometryCollection
 * @param {Array<Geometry>} geometries an array of GeoJSON Geometries
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<GeometryCollection>} a GeoJSON GeometryCollection Feature
 * @example
 * var pt = turf.geometry("Point", [100, 0]);
 * var line = turf.geometry("LineString", [[101, 0], [102, 1]]);
 * var collection = turf.geometryCollection([pt, line]);
 *
 * // => collection
 */
function geometryCollection(geometries, properties, options) {
    if (options === void 0) { options = {}; }
    var geom = {
        type: "GeometryCollection",
        geometries: geometries,
    };
    return feature(geom, properties, options);
}
exports.geometryCollection = geometryCollection;
/**
 * Round number to precision
 *
 * @param {number} num Number
 * @param {number} [precision=0] Precision
 * @returns {number} rounded number
 * @example
 * turf.round(120.4321)
 * //=120
 *
 * turf.round(120.4321, 2)
 * //=120.43
 */
function round(num, precision) {
    if (precision === void 0) { precision = 0; }
    if (precision && !(precision >= 0)) {
        throw new Error("precision must be a positive number");
    }
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(num * multiplier) / multiplier;
}
exports.round = round;
/**
 * Convert a distance measurement (assuming a spherical Earth) from radians to a more friendly unit.
 * Valid units: miles, nauticalmiles, inches, yards, meters, metres, kilometers, centimeters, feet
 *
 * @name radiansToLength
 * @param {number} radians in radians across the sphere
 * @param {string} [units="kilometers"] can be degrees, radians, miles, inches, yards, metres,
 * meters, kilometres, kilometers.
 * @returns {number} distance
 */
function radiansToLength(radians, units) {
    if (units === void 0) { units = "kilometers"; }
    var factor = exports.factors[units];
    if (!factor) {
        throw new Error(units + " units is invalid");
    }
    return radians * factor;
}
exports.radiansToLength = radiansToLength;
/**
 * Convert a distance measurement (assuming a spherical Earth) from a real-world unit into radians
 * Valid units: miles, nauticalmiles, inches, yards, meters, metres, kilometers, centimeters, feet
 *
 * @name lengthToRadians
 * @param {number} distance in real units
 * @param {string} [units="kilometers"] can be degrees, radians, miles, inches, yards, metres,
 * meters, kilometres, kilometers.
 * @returns {number} radians
 */
function lengthToRadians(distance, units) {
    if (units === void 0) { units = "kilometers"; }
    var factor = exports.factors[units];
    if (!factor) {
        throw new Error(units + " units is invalid");
    }
    return distance / factor;
}
exports.lengthToRadians = lengthToRadians;
/**
 * Convert a distance measurement (assuming a spherical Earth) from a real-world unit into degrees
 * Valid units: miles, nauticalmiles, inches, yards, meters, metres, centimeters, kilometres, feet
 *
 * @name lengthToDegrees
 * @param {number} distance in real units
 * @param {string} [units="kilometers"] can be degrees, radians, miles, inches, yards, metres,
 * meters, kilometres, kilometers.
 * @returns {number} degrees
 */
function lengthToDegrees(distance, units) {
    return radiansToDegrees(lengthToRadians(distance, units));
}
exports.lengthToDegrees = lengthToDegrees;
/**
 * Converts any bearing angle from the north line direction (positive clockwise)
 * and returns an angle between 0-360 degrees (positive clockwise), 0 being the north line
 *
 * @name bearingToAzimuth
 * @param {number} bearing angle, between -180 and +180 degrees
 * @returns {number} angle between 0 and 360 degrees
 */
function bearingToAzimuth(bearing) {
    var angle = bearing % 360;
    if (angle < 0) {
        angle += 360;
    }
    return angle;
}
exports.bearingToAzimuth = bearingToAzimuth;
/**
 * Converts an angle in radians to degrees
 *
 * @name radiansToDegrees
 * @param {number} radians angle in radians
 * @returns {number} degrees between 0 and 360 degrees
 */
function radiansToDegrees(radians) {
    var degrees = radians % (2 * Math.PI);
    return (degrees * 180) / Math.PI;
}
exports.radiansToDegrees = radiansToDegrees;
/**
 * Converts an angle in degrees to radians
 *
 * @name degreesToRadians
 * @param {number} degrees angle between 0 and 360 degrees
 * @returns {number} angle in radians
 */
function degreesToRadians(degrees) {
    var radians = degrees % 360;
    return (radians * Math.PI) / 180;
}
exports.degreesToRadians = degreesToRadians;
/**
 * Converts a length to the requested unit.
 * Valid units: miles, nauticalmiles, inches, yards, meters, metres, kilometers, centimeters, feet
 *
 * @param {number} length to be converted
 * @param {Units} [originalUnit="kilometers"] of the length
 * @param {Units} [finalUnit="kilometers"] returned unit
 * @returns {number} the converted length
 */
function convertLength(length, originalUnit, finalUnit) {
    if (originalUnit === void 0) { originalUnit = "kilometers"; }
    if (finalUnit === void 0) { finalUnit = "kilometers"; }
    if (!(length >= 0)) {
        throw new Error("length must be a positive number");
    }
    return radiansToLength(lengthToRadians(length, originalUnit), finalUnit);
}
exports.convertLength = convertLength;
/**
 * Converts a area to the requested unit.
 * Valid units: kilometers, kilometres, meters, metres, centimetres, millimeters, acres, miles, yards, feet, inches, hectares
 * @param {number} area to be converted
 * @param {Units} [originalUnit="meters"] of the distance
 * @param {Units} [finalUnit="kilometers"] returned unit
 * @returns {number} the converted area
 */
function convertArea(area, originalUnit, finalUnit) {
    if (originalUnit === void 0) { originalUnit = "meters"; }
    if (finalUnit === void 0) { finalUnit = "kilometers"; }
    if (!(area >= 0)) {
        throw new Error("area must be a positive number");
    }
    var startFactor = exports.areaFactors[originalUnit];
    if (!startFactor) {
        throw new Error("invalid original units");
    }
    var finalFactor = exports.areaFactors[finalUnit];
    if (!finalFactor) {
        throw new Error("invalid final units");
    }
    return (area / startFactor) * finalFactor;
}
exports.convertArea = convertArea;
/**
 * isNumber
 *
 * @param {*} num Number to validate
 * @returns {boolean} true/false
 * @example
 * turf.isNumber(123)
 * //=true
 * turf.isNumber('foo')
 * //=false
 */
function isNumber(num) {
    return !isNaN(num) && num !== null && !Array.isArray(num);
}
exports.isNumber = isNumber;
/**
 * isObject
 *
 * @param {*} input variable to validate
 * @returns {boolean} true/false
 * @example
 * turf.isObject({elevation: 10})
 * //=true
 * turf.isObject('foo')
 * //=false
 */
function isObject(input) {
    return !!input && input.constructor === Object;
}
exports.isObject = isObject;
/**
 * Validate BBox
 *
 * @private
 * @param {Array<number>} bbox BBox to validate
 * @returns {void}
 * @throws Error if BBox is not valid
 * @example
 * validateBBox([-180, -40, 110, 50])
 * //=OK
 * validateBBox([-180, -40])
 * //=Error
 * validateBBox('Foo')
 * //=Error
 * validateBBox(5)
 * //=Error
 * validateBBox(null)
 * //=Error
 * validateBBox(undefined)
 * //=Error
 */
function validateBBox(bbox) {
    if (!bbox) {
        throw new Error("bbox is required");
    }
    if (!Array.isArray(bbox)) {
        throw new Error("bbox must be an Array");
    }
    if (bbox.length !== 4 && bbox.length !== 6) {
        throw new Error("bbox must be an Array of 4 or 6 numbers");
    }
    bbox.forEach(function (num) {
        if (!isNumber(num)) {
            throw new Error("bbox must only contain numbers");
        }
    });
}
exports.validateBBox = validateBBox;
/**
 * Validate Id
 *
 * @private
 * @param {string|number} id Id to validate
 * @returns {void}
 * @throws Error if Id is not valid
 * @example
 * validateId([-180, -40, 110, 50])
 * //=Error
 * validateId([-180, -40])
 * //=Error
 * validateId('Foo')
 * //=OK
 * validateId(5)
 * //=OK
 * validateId(null)
 * //=Error
 * validateId(undefined)
 * //=Error
 */
function validateId(id) {
    if (!id) {
        throw new Error("id is required");
    }
    if (["string", "number"].indexOf(typeof id) === -1) {
        throw new Error("id must be a number or a string");
    }
}
exports.validateId = validateId;

},{}],3:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var helpers_1 = require("@turf/helpers");
/**
 * Unwrap a coordinate from a Point Feature, Geometry or a single coordinate.
 *
 * @name getCoord
 * @param {Array<number>|Geometry<Point>|Feature<Point>} coord GeoJSON Point or an Array of numbers
 * @returns {Array<number>} coordinates
 * @example
 * var pt = turf.point([10, 10]);
 *
 * var coord = turf.getCoord(pt);
 * //= [10, 10]
 */
function getCoord(coord) {
    if (!coord) {
        throw new Error("coord is required");
    }
    if (!Array.isArray(coord)) {
        if (coord.type === "Feature" &&
            coord.geometry !== null &&
            coord.geometry.type === "Point") {
            return coord.geometry.coordinates;
        }
        if (coord.type === "Point") {
            return coord.coordinates;
        }
    }
    if (Array.isArray(coord) &&
        coord.length >= 2 &&
        !Array.isArray(coord[0]) &&
        !Array.isArray(coord[1])) {
        return coord;
    }
    throw new Error("coord must be GeoJSON Point or an Array of numbers");
}
exports.getCoord = getCoord;
/**
 * Unwrap coordinates from a Feature, Geometry Object or an Array
 *
 * @name getCoords
 * @param {Array<any>|Geometry|Feature} coords Feature, Geometry Object or an Array
 * @returns {Array<any>} coordinates
 * @example
 * var poly = turf.polygon([[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]);
 *
 * var coords = turf.getCoords(poly);
 * //= [[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]
 */
function getCoords(coords) {
    if (Array.isArray(coords)) {
        return coords;
    }
    // Feature
    if (coords.type === "Feature") {
        if (coords.geometry !== null) {
            return coords.geometry.coordinates;
        }
    }
    else {
        // Geometry
        if (coords.coordinates) {
            return coords.coordinates;
        }
    }
    throw new Error("coords must be GeoJSON Feature, Geometry Object or an Array");
}
exports.getCoords = getCoords;
/**
 * Checks if coordinates contains a number
 *
 * @name containsNumber
 * @param {Array<any>} coordinates GeoJSON Coordinates
 * @returns {boolean} true if Array contains a number
 */
function containsNumber(coordinates) {
    if (coordinates.length > 1 &&
        helpers_1.isNumber(coordinates[0]) &&
        helpers_1.isNumber(coordinates[1])) {
        return true;
    }
    if (Array.isArray(coordinates[0]) && coordinates[0].length) {
        return containsNumber(coordinates[0]);
    }
    throw new Error("coordinates must only contain numbers");
}
exports.containsNumber = containsNumber;
/**
 * Enforce expectations about types of GeoJSON objects for Turf.
 *
 * @name geojsonType
 * @param {GeoJSON} value any GeoJSON object
 * @param {string} type expected GeoJSON type
 * @param {string} name name of calling function
 * @throws {Error} if value is not the expected type.
 */
function geojsonType(value, type, name) {
    if (!type || !name) {
        throw new Error("type and name required");
    }
    if (!value || value.type !== type) {
        throw new Error("Invalid input to " +
            name +
            ": must be a " +
            type +
            ", given " +
            value.type);
    }
}
exports.geojsonType = geojsonType;
/**
 * Enforce expectations about types of {@link Feature} inputs for Turf.
 * Internally this uses {@link geojsonType} to judge geometry types.
 *
 * @name featureOf
 * @param {Feature} feature a feature with an expected geometry type
 * @param {string} type expected GeoJSON type
 * @param {string} name name of calling function
 * @throws {Error} error if value is not the expected type.
 */
function featureOf(feature, type, name) {
    if (!feature) {
        throw new Error("No feature passed");
    }
    if (!name) {
        throw new Error(".featureOf() requires a name");
    }
    if (!feature || feature.type !== "Feature" || !feature.geometry) {
        throw new Error("Invalid input to " + name + ", Feature with geometry required");
    }
    if (!feature.geometry || feature.geometry.type !== type) {
        throw new Error("Invalid input to " +
            name +
            ": must be a " +
            type +
            ", given " +
            feature.geometry.type);
    }
}
exports.featureOf = featureOf;
/**
 * Enforce expectations about types of {@link FeatureCollection} inputs for Turf.
 * Internally this uses {@link geojsonType} to judge geometry types.
 *
 * @name collectionOf
 * @param {FeatureCollection} featureCollection a FeatureCollection for which features will be judged
 * @param {string} type expected GeoJSON type
 * @param {string} name name of calling function
 * @throws {Error} if value is not the expected type.
 */
function collectionOf(featureCollection, type, name) {
    if (!featureCollection) {
        throw new Error("No featureCollection passed");
    }
    if (!name) {
        throw new Error(".collectionOf() requires a name");
    }
    if (!featureCollection || featureCollection.type !== "FeatureCollection") {
        throw new Error("Invalid input to " + name + ", FeatureCollection required");
    }
    for (var _i = 0, _a = featureCollection.features; _i < _a.length; _i++) {
        var feature = _a[_i];
        if (!feature || feature.type !== "Feature" || !feature.geometry) {
            throw new Error("Invalid input to " + name + ", Feature with geometry required");
        }
        if (!feature.geometry || feature.geometry.type !== type) {
            throw new Error("Invalid input to " +
                name +
                ": must be a " +
                type +
                ", given " +
                feature.geometry.type);
        }
    }
}
exports.collectionOf = collectionOf;
/**
 * Get Geometry from Feature or Geometry Object
 *
 * @param {Feature|Geometry} geojson GeoJSON Feature or Geometry Object
 * @returns {Geometry|null} GeoJSON Geometry Object
 * @throws {Error} if geojson is not a Feature or Geometry Object
 * @example
 * var point = {
 *   "type": "Feature",
 *   "properties": {},
 *   "geometry": {
 *     "type": "Point",
 *     "coordinates": [110, 40]
 *   }
 * }
 * var geom = turf.getGeom(point)
 * //={"type": "Point", "coordinates": [110, 40]}
 */
function getGeom(geojson) {
    if (geojson.type === "Feature") {
        return geojson.geometry;
    }
    return geojson;
}
exports.getGeom = getGeom;
/**
 * Get GeoJSON object's type, Geometry type is prioritize.
 *
 * @param {GeoJSON} geojson GeoJSON object
 * @param {string} [name="geojson"] name of the variable to display in error message (unused)
 * @returns {string} GeoJSON type
 * @example
 * var point = {
 *   "type": "Feature",
 *   "properties": {},
 *   "geometry": {
 *     "type": "Point",
 *     "coordinates": [110, 40]
 *   }
 * }
 * var geom = turf.getType(point)
 * //="Point"
 */
function getType(geojson, _name) {
    if (geojson.type === "FeatureCollection") {
        return "FeatureCollection";
    }
    if (geojson.type === "GeometryCollection") {
        return "GeometryCollection";
    }
    if (geojson.type === "Feature" && geojson.geometry !== null) {
        return geojson.geometry.type;
    }
    return geojson.type;
}
exports.getType = getType;

},{"@turf/helpers":2}],4:[function(require,module,exports){
'use strict';

module.exports = decode;

var keys, values, lengths, dim, e;

var geometryTypes = [
    'Point', 'MultiPoint', 'LineString', 'MultiLineString',
    'Polygon', 'MultiPolygon', 'GeometryCollection'];

function decode(pbf) {
    dim = 2;
    e = Math.pow(10, 6);
    lengths = null;

    keys = [];
    values = [];
    var obj = pbf.readFields(readDataField, {});
    keys = null;

    return obj;
}

function readDataField(tag, obj, pbf) {
    if (tag === 1) keys.push(pbf.readString());
    else if (tag === 2) dim = pbf.readVarint();
    else if (tag === 3) e = Math.pow(10, pbf.readVarint());

    else if (tag === 4) readFeatureCollection(pbf, obj);
    else if (tag === 5) readFeature(pbf, obj);
    else if (tag === 6) readGeometry(pbf, obj);
}

function readFeatureCollection(pbf, obj) {
    obj.type = 'FeatureCollection';
    obj.features = [];
    return pbf.readMessage(readFeatureCollectionField, obj);
}

function readFeature(pbf, feature) {
    feature.type = 'Feature';
    var f = pbf.readMessage(readFeatureField, feature);
    if (!('geometry' in f)) f.geometry = null;
    return f;
}

function readGeometry(pbf, geom) {
    geom.type = 'Point';
    return pbf.readMessage(readGeometryField, geom);
}

function readFeatureCollectionField(tag, obj, pbf) {
    if (tag === 1) obj.features.push(readFeature(pbf, {}));

    else if (tag === 13) values.push(readValue(pbf));
    else if (tag === 15) readProps(pbf, obj);
}

function readFeatureField(tag, feature, pbf) {
    if (tag === 1) feature.geometry = readGeometry(pbf, {});

    else if (tag === 11) feature.id = pbf.readString();
    else if (tag === 12) feature.id = pbf.readSVarint();

    else if (tag === 13) values.push(readValue(pbf));
    else if (tag === 14) feature.properties = readProps(pbf, {});
    else if (tag === 15) readProps(pbf, feature);
}

function readGeometryField(tag, geom, pbf) {
    if (tag === 1) geom.type = geometryTypes[pbf.readVarint()];

    else if (tag === 2) lengths = pbf.readPackedVarint();
    else if (tag === 3) readCoords(geom, pbf, geom.type);
    else if (tag === 4) {
        geom.geometries = geom.geometries || [];
        geom.geometries.push(readGeometry(pbf, {}));
    }
    else if (tag === 13) values.push(readValue(pbf));
    else if (tag === 15) readProps(pbf, geom);
}

function readCoords(geom, pbf, type) {
    if (type === 'Point') geom.coordinates = readPoint(pbf);
    else if (type === 'MultiPoint') geom.coordinates = readLine(pbf, true);
    else if (type === 'LineString') geom.coordinates = readLine(pbf);
    else if (type === 'MultiLineString') geom.coordinates = readMultiLine(pbf);
    else if (type === 'Polygon') geom.coordinates = readMultiLine(pbf, true);
    else if (type === 'MultiPolygon') geom.coordinates = readMultiPolygon(pbf);
}

function readValue(pbf) {
    var end = pbf.readVarint() + pbf.pos,
        value = null;

    while (pbf.pos < end) {
        var val = pbf.readVarint(),
            tag = val >> 3;

        if (tag === 1) value = pbf.readString();
        else if (tag === 2) value = pbf.readDouble();
        else if (tag === 3) value = pbf.readVarint();
        else if (tag === 4) value = -pbf.readVarint();
        else if (tag === 5) value = pbf.readBoolean();
        else if (tag === 6) value = JSON.parse(pbf.readString());
    }
    return value;
}

function readProps(pbf, props) {
    var end = pbf.readVarint() + pbf.pos;
    while (pbf.pos < end) props[keys[pbf.readVarint()]] = values[pbf.readVarint()];
    values = [];
    return props;
}

function readPoint(pbf) {
    var end = pbf.readVarint() + pbf.pos,
        coords = [];
    while (pbf.pos < end) coords.push(pbf.readSVarint() / e);
    return coords;
}

function readLinePart(pbf, end, len, closed) {
    var i = 0,
        coords = [],
        p, d;

    var prevP = [];
    for (d = 0; d < dim; d++) prevP[d] = 0;

    while (len ? i < len : pbf.pos < end) {
        p = [];
        for (d = 0; d < dim; d++) {
            prevP[d] += pbf.readSVarint();
            p[d] = prevP[d] / e;
        }
        coords.push(p);
        i++;
    }
    if (closed) coords.push(coords[0]);

    return coords;
}

function readLine(pbf) {
    return readLinePart(pbf, pbf.readVarint() + pbf.pos);
}

function readMultiLine(pbf, closed) {
    var end = pbf.readVarint() + pbf.pos;
    if (!lengths) return [readLinePart(pbf, end, null, closed)];

    var coords = [];
    for (var i = 0; i < lengths.length; i++) coords.push(readLinePart(pbf, end, lengths[i], closed));
    lengths = null;
    return coords;
}

function readMultiPolygon(pbf) {
    var end = pbf.readVarint() + pbf.pos;
    if (!lengths) return [[readLinePart(pbf, end, null, true)]];

    var coords = [];
    var j = 1;
    for (var i = 0; i < lengths[0]; i++) {
        var rings = [];
        for (var k = 0; k < lengths[j]; k++) rings.push(readLinePart(pbf, end, lengths[j + 1 + k], true));
        j += lengths[j] + 1;
        coords.push(rings);
    }
    lengths = null;
    return coords;
}

},{}],5:[function(require,module,exports){
'use strict';

module.exports = encode;

var keys, keysNum, keysArr, dim, e,
    maxPrecision = 1e6;

var geometryTypes = {
    'Point': 0,
    'MultiPoint': 1,
    'LineString': 2,
    'MultiLineString': 3,
    'Polygon': 4,
    'MultiPolygon': 5,
    'GeometryCollection': 6
};

function encode(obj, pbf) {
    keys = {};
    keysArr = [];
    keysNum = 0;
    dim = 0;
    e = 1;

    analyze(obj);

    e = Math.min(e, maxPrecision);
    var precision = Math.ceil(Math.log(e) / Math.LN10);

    for (var i = 0; i < keysArr.length; i++) pbf.writeStringField(1, keysArr[i]);
    if (dim !== 2) pbf.writeVarintField(2, dim);
    if (precision !== 6) pbf.writeVarintField(3, precision);

    if (obj.type === 'FeatureCollection') pbf.writeMessage(4, writeFeatureCollection, obj);
    else if (obj.type === 'Feature') pbf.writeMessage(5, writeFeature, obj);
    else pbf.writeMessage(6, writeGeometry, obj);

    keys = null;

    return pbf.finish();
}

function analyze(obj) {
    var i, key;

    if (obj.type === 'FeatureCollection') {
        for (i = 0; i < obj.features.length; i++) analyze(obj.features[i]);

    } else if (obj.type === 'Feature') {
        if (obj.geometry !== null) analyze(obj.geometry);
        for (key in obj.properties) saveKey(key);

    } else if (obj.type === 'Point') analyzePoint(obj.coordinates);
    else if (obj.type === 'MultiPoint') analyzePoints(obj.coordinates);
    else if (obj.type === 'GeometryCollection') {
        for (i = 0; i < obj.geometries.length; i++) analyze(obj.geometries[i]);
    }
    else if (obj.type === 'LineString') analyzePoints(obj.coordinates);
    else if (obj.type === 'Polygon' || obj.type === 'MultiLineString') analyzeMultiLine(obj.coordinates);
    else if (obj.type === 'MultiPolygon') {
        for (i = 0; i < obj.coordinates.length; i++) analyzeMultiLine(obj.coordinates[i]);
    }

    for (key in obj) {
        if (!isSpecialKey(key, obj.type)) saveKey(key);
    }
}

function analyzeMultiLine(coords) {
    for (var i = 0; i < coords.length; i++) analyzePoints(coords[i]);
}

function analyzePoints(coords) {
    for (var i = 0; i < coords.length; i++) analyzePoint(coords[i]);
}

function analyzePoint(point) {
    dim = Math.max(dim, point.length);

    // find max precision
    for (var i = 0; i < point.length; i++) {
        while (Math.round(point[i] * e) / e !== point[i] && e < maxPrecision) e *= 10;
    }
}

function saveKey(key) {
    if (keys[key] === undefined) {
        keysArr.push(key);
        keys[key] = keysNum++;
    }
}

function writeFeatureCollection(obj, pbf) {
    for (var i = 0; i < obj.features.length; i++) {
        pbf.writeMessage(1, writeFeature, obj.features[i]);
    }
    writeProps(obj, pbf, true);
}

function writeFeature(feature, pbf) {
    if (feature.geometry !== null) pbf.writeMessage(1, writeGeometry, feature.geometry);

    if (feature.id !== undefined) {
        if (typeof feature.id === 'number' && feature.id % 1 === 0) pbf.writeSVarintField(12, feature.id);
        else pbf.writeStringField(11, feature.id);
    }

    if (feature.properties) writeProps(feature.properties, pbf);
    writeProps(feature, pbf, true);
}

function writeGeometry(geom, pbf) {
    pbf.writeVarintField(1, geometryTypes[geom.type]);

    var coords = geom.coordinates;

    if (geom.type === 'Point') writePoint(coords, pbf);
    else if (geom.type === 'MultiPoint') writeLine(coords, pbf, true);
    else if (geom.type === 'LineString') writeLine(coords, pbf);
    else if (geom.type === 'MultiLineString') writeMultiLine(coords, pbf);
    else if (geom.type === 'Polygon') writeMultiLine(coords, pbf, true);
    else if (geom.type === 'MultiPolygon') writeMultiPolygon(coords, pbf);
    else if (geom.type === 'GeometryCollection') {
        for (var i = 0; i < geom.geometries.length; i++) pbf.writeMessage(4, writeGeometry, geom.geometries[i]);
    }

    writeProps(geom, pbf, true);
}

function writeProps(props, pbf, isCustom) {
    var indexes = [],
        valueIndex = 0;

    for (var key in props) {
        if (isCustom && isSpecialKey(key, props.type)) {
            continue;
        }
        pbf.writeMessage(13, writeValue, props[key]);
        indexes.push(keys[key]);
        indexes.push(valueIndex++);
    }
    pbf.writePackedVarint(isCustom ? 15 : 14, indexes);
}

function writeValue(value, pbf) {
    if (value === null) return;

    var type = typeof value;

    if (type === 'string') pbf.writeStringField(1, value);
    else if (type === 'boolean') pbf.writeBooleanField(5, value);
    else if (type === 'object') pbf.writeStringField(6, JSON.stringify(value));
    else if (type === 'number') {
        if (value % 1 !== 0) pbf.writeDoubleField(2, value);
        else if (value >= 0) pbf.writeVarintField(3, value);
        else pbf.writeVarintField(4, -value);
    }
}

function writePoint(point, pbf) {
    var coords = [];
    for (var i = 0; i < dim; i++) coords.push(Math.round(point[i] * e));
    pbf.writePackedSVarint(3, coords);
}

function writeLine(line, pbf) {
    var coords = [];
    populateLine(coords, line);
    pbf.writePackedSVarint(3, coords);
}

function writeMultiLine(lines, pbf, closed) {
    var len = lines.length,
        i;
    if (len !== 1) {
        var lengths = [];
        for (i = 0; i < len; i++) lengths.push(lines[i].length - (closed ? 1 : 0));
        pbf.writePackedVarint(2, lengths);
        // TODO faster with custom writeMessage?
    }
    var coords = [];
    for (i = 0; i < len; i++) populateLine(coords, lines[i], closed);
    pbf.writePackedSVarint(3, coords);
}

function writeMultiPolygon(polygons, pbf) {
    var len = polygons.length,
        i, j;
    if (len !== 1 || polygons[0].length !== 1) {
        var lengths = [len];
        for (i = 0; i < len; i++) {
            lengths.push(polygons[i].length);
            for (j = 0; j < polygons[i].length; j++) lengths.push(polygons[i][j].length - 1);
        }
        pbf.writePackedVarint(2, lengths);
    }

    var coords = [];
    for (i = 0; i < len; i++) {
        for (j = 0; j < polygons[i].length; j++) populateLine(coords, polygons[i][j], true);
    }
    pbf.writePackedSVarint(3, coords);
}

function populateLine(coords, line, closed) {
    var i, j,
        len = line.length - (closed ? 1 : 0),
        sum = new Array(dim);
    for (j = 0; j < dim; j++) sum[j] = 0;
    for (i = 0; i < len; i++) {
        for (j = 0; j < dim; j++) {
            var n = Math.round(line[i][j] * e) - sum[j];
            coords.push(n);
            sum[j] += n;
        }
    }
}

function isSpecialKey(key, type) {
    if (key === 'type') return true;
    else if (type === 'FeatureCollection') {
        if (key === 'features') return true;
    } else if (type === 'Feature') {
        if (key === 'id' || key === 'properties' || key === 'geometry') return true;
    } else if (type === 'GeometryCollection') {
        if (key === 'geometries') return true;
    } else if (key === 'coordinates') return true;
    return false;
}

},{}],6:[function(require,module,exports){
'use strict';

exports.encode = require('./encode');
exports.decode = require('./decode');

},{"./decode":4,"./encode":5}],7:[function(require,module,exports){
/*! ieee754. BSD-3-Clause License. Feross Aboukhadijeh <https://feross.org/opensource> */
exports.read = function (buffer, offset, isLE, mLen, nBytes) {
  var e, m
  var eLen = (nBytes * 8) - mLen - 1
  var eMax = (1 << eLen) - 1
  var eBias = eMax >> 1
  var nBits = -7
  var i = isLE ? (nBytes - 1) : 0
  var d = isLE ? -1 : 1
  var s = buffer[offset + i]

  i += d

  e = s & ((1 << (-nBits)) - 1)
  s >>= (-nBits)
  nBits += eLen
  for (; nBits > 0; e = (e * 256) + buffer[offset + i], i += d, nBits -= 8) {}

  m = e & ((1 << (-nBits)) - 1)
  e >>= (-nBits)
  nBits += mLen
  for (; nBits > 0; m = (m * 256) + buffer[offset + i], i += d, nBits -= 8) {}

  if (e === 0) {
    e = 1 - eBias
  } else if (e === eMax) {
    return m ? NaN : ((s ? -1 : 1) * Infinity)
  } else {
    m = m + Math.pow(2, mLen)
    e = e - eBias
  }
  return (s ? -1 : 1) * m * Math.pow(2, e - mLen)
}

exports.write = function (buffer, value, offset, isLE, mLen, nBytes) {
  var e, m, c
  var eLen = (nBytes * 8) - mLen - 1
  var eMax = (1 << eLen) - 1
  var eBias = eMax >> 1
  var rt = (mLen === 23 ? Math.pow(2, -24) - Math.pow(2, -77) : 0)
  var i = isLE ? 0 : (nBytes - 1)
  var d = isLE ? 1 : -1
  var s = value < 0 || (value === 0 && 1 / value < 0) ? 1 : 0

  value = Math.abs(value)

  if (isNaN(value) || value === Infinity) {
    m = isNaN(value) ? 1 : 0
    e = eMax
  } else {
    e = Math.floor(Math.log(value) / Math.LN2)
    if (value * (c = Math.pow(2, -e)) < 1) {
      e--
      c *= 2
    }
    if (e + eBias >= 1) {
      value += rt / c
    } else {
      value += rt * Math.pow(2, 1 - eBias)
    }
    if (value * c >= 2) {
      e++
      c /= 2
    }

    if (e + eBias >= eMax) {
      m = 0
      e = eMax
    } else if (e + eBias >= 1) {
      m = ((value * c) - 1) * Math.pow(2, mLen)
      e = e + eBias
    } else {
      m = value * Math.pow(2, eBias - 1) * Math.pow(2, mLen)
      e = 0
    }
  }

  for (; mLen >= 8; buffer[offset + i] = m & 0xff, i += d, m /= 256, mLen -= 8) {}

  e = (e << mLen) | m
  eLen += mLen
  for (; eLen > 0; buffer[offset + i] = e & 0xff, i += d, e /= 256, eLen -= 8) {}

  buffer[offset + i - d] |= s * 128
}

},{}],8:[function(require,module,exports){
'use strict';

module.exports = Pbf;

var ieee754 = require('ieee754');

function Pbf(buf) {
    this.buf = ArrayBuffer.isView && ArrayBuffer.isView(buf) ? buf : new Uint8Array(buf || 0);
    this.pos = 0;
    this.type = 0;
    this.length = this.buf.length;
}

Pbf.Varint  = 0; // varint: int32, int64, uint32, uint64, sint32, sint64, bool, enum
Pbf.Fixed64 = 1; // 64-bit: double, fixed64, sfixed64
Pbf.Bytes   = 2; // length-delimited: string, bytes, embedded messages, packed repeated fields
Pbf.Fixed32 = 5; // 32-bit: float, fixed32, sfixed32

var SHIFT_LEFT_32 = (1 << 16) * (1 << 16),
    SHIFT_RIGHT_32 = 1 / SHIFT_LEFT_32;

// Threshold chosen based on both benchmarking and knowledge about browser string
// data structures (which currently switch structure types at 12 bytes or more)
var TEXT_DECODER_MIN_LENGTH = 12;
var utf8TextDecoder = typeof TextDecoder === 'undefined' ? null : new TextDecoder('utf8');

Pbf.prototype = {

    destroy: function() {
        this.buf = null;
    },

    // === READING =================================================================

    readFields: function(readField, result, end) {
        end = end || this.length;

        while (this.pos < end) {
            var val = this.readVarint(),
                tag = val >> 3,
                startPos = this.pos;

            this.type = val & 0x7;
            readField(tag, result, this);

            if (this.pos === startPos) this.skip(val);
        }
        return result;
    },

    readMessage: function(readField, result) {
        return this.readFields(readField, result, this.readVarint() + this.pos);
    },

    readFixed32: function() {
        var val = readUInt32(this.buf, this.pos);
        this.pos += 4;
        return val;
    },

    readSFixed32: function() {
        var val = readInt32(this.buf, this.pos);
        this.pos += 4;
        return val;
    },

    // 64-bit int handling is based on github.com/dpw/node-buffer-more-ints (MIT-licensed)

    readFixed64: function() {
        var val = readUInt32(this.buf, this.pos) + readUInt32(this.buf, this.pos + 4) * SHIFT_LEFT_32;
        this.pos += 8;
        return val;
    },

    readSFixed64: function() {
        var val = readUInt32(this.buf, this.pos) + readInt32(this.buf, this.pos + 4) * SHIFT_LEFT_32;
        this.pos += 8;
        return val;
    },

    readFloat: function() {
        var val = ieee754.read(this.buf, this.pos, true, 23, 4);
        this.pos += 4;
        return val;
    },

    readDouble: function() {
        var val = ieee754.read(this.buf, this.pos, true, 52, 8);
        this.pos += 8;
        return val;
    },

    readVarint: function(isSigned) {
        var buf = this.buf,
            val, b;

        b = buf[this.pos++]; val  =  b & 0x7f;        if (b < 0x80) return val;
        b = buf[this.pos++]; val |= (b & 0x7f) << 7;  if (b < 0x80) return val;
        b = buf[this.pos++]; val |= (b & 0x7f) << 14; if (b < 0x80) return val;
        b = buf[this.pos++]; val |= (b & 0x7f) << 21; if (b < 0x80) return val;
        b = buf[this.pos];   val |= (b & 0x0f) << 28;

        return readVarintRemainder(val, isSigned, this);
    },

    readVarint64: function() { // for compatibility with v2.0.1
        return this.readVarint(true);
    },

    readSVarint: function() {
        var num = this.readVarint();
        return num % 2 === 1 ? (num + 1) / -2 : num / 2; // zigzag encoding
    },

    readBoolean: function() {
        return Boolean(this.readVarint());
    },

    readString: function() {
        var end = this.readVarint() + this.pos;
        var pos = this.pos;
        this.pos = end;

        if (end - pos >= TEXT_DECODER_MIN_LENGTH && utf8TextDecoder) {
            // longer strings are fast with the built-in browser TextDecoder API
            return readUtf8TextDecoder(this.buf, pos, end);
        }
        // short strings are fast with our custom implementation
        return readUtf8(this.buf, pos, end);
    },

    readBytes: function() {
        var end = this.readVarint() + this.pos,
            buffer = this.buf.subarray(this.pos, end);
        this.pos = end;
        return buffer;
    },

    // verbose for performance reasons; doesn't affect gzipped size

    readPackedVarint: function(arr, isSigned) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readVarint(isSigned));
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readVarint(isSigned));
        return arr;
    },
    readPackedSVarint: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readSVarint());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readSVarint());
        return arr;
    },
    readPackedBoolean: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readBoolean());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readBoolean());
        return arr;
    },
    readPackedFloat: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readFloat());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readFloat());
        return arr;
    },
    readPackedDouble: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readDouble());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readDouble());
        return arr;
    },
    readPackedFixed32: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readFixed32());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readFixed32());
        return arr;
    },
    readPackedSFixed32: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readSFixed32());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readSFixed32());
        return arr;
    },
    readPackedFixed64: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readFixed64());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readFixed64());
        return arr;
    },
    readPackedSFixed64: function(arr) {
        if (this.type !== Pbf.Bytes) return arr.push(this.readSFixed64());
        var end = readPackedEnd(this);
        arr = arr || [];
        while (this.pos < end) arr.push(this.readSFixed64());
        return arr;
    },

    skip: function(val) {
        var type = val & 0x7;
        if (type === Pbf.Varint) while (this.buf[this.pos++] > 0x7f) {}
        else if (type === Pbf.Bytes) this.pos = this.readVarint() + this.pos;
        else if (type === Pbf.Fixed32) this.pos += 4;
        else if (type === Pbf.Fixed64) this.pos += 8;
        else throw new Error('Unimplemented type: ' + type);
    },

    // === WRITING =================================================================

    writeTag: function(tag, type) {
        this.writeVarint((tag << 3) | type);
    },

    realloc: function(min) {
        var length = this.length || 16;

        while (length < this.pos + min) length *= 2;

        if (length !== this.length) {
            var buf = new Uint8Array(length);
            buf.set(this.buf);
            this.buf = buf;
            this.length = length;
        }
    },

    finish: function() {
        this.length = this.pos;
        this.pos = 0;
        return this.buf.subarray(0, this.length);
    },

    writeFixed32: function(val) {
        this.realloc(4);
        writeInt32(this.buf, val, this.pos);
        this.pos += 4;
    },

    writeSFixed32: function(val) {
        this.realloc(4);
        writeInt32(this.buf, val, this.pos);
        this.pos += 4;
    },

    writeFixed64: function(val) {
        this.realloc(8);
        writeInt32(this.buf, val & -1, this.pos);
        writeInt32(this.buf, Math.floor(val * SHIFT_RIGHT_32), this.pos + 4);
        this.pos += 8;
    },

    writeSFixed64: function(val) {
        this.realloc(8);
        writeInt32(this.buf, val & -1, this.pos);
        writeInt32(this.buf, Math.floor(val * SHIFT_RIGHT_32), this.pos + 4);
        this.pos += 8;
    },

    writeVarint: function(val) {
        val = +val || 0;

        if (val > 0xfffffff || val < 0) {
            writeBigVarint(val, this);
            return;
        }

        this.realloc(4);

        this.buf[this.pos++] =           val & 0x7f  | (val > 0x7f ? 0x80 : 0); if (val <= 0x7f) return;
        this.buf[this.pos++] = ((val >>>= 7) & 0x7f) | (val > 0x7f ? 0x80 : 0); if (val <= 0x7f) return;
        this.buf[this.pos++] = ((val >>>= 7) & 0x7f) | (val > 0x7f ? 0x80 : 0); if (val <= 0x7f) return;
        this.buf[this.pos++] =   (val >>> 7) & 0x7f;
    },

    writeSVarint: function(val) {
        this.writeVarint(val < 0 ? -val * 2 - 1 : val * 2);
    },

    writeBoolean: function(val) {
        this.writeVarint(Boolean(val));
    },

    writeString: function(str) {
        str = String(str);
        this.realloc(str.length * 4);

        this.pos++; // reserve 1 byte for short string length

        var startPos = this.pos;
        // write the string directly to the buffer and see how much was written
        this.pos = writeUtf8(this.buf, str, this.pos);
        var len = this.pos - startPos;

        if (len >= 0x80) makeRoomForExtraLength(startPos, len, this);

        // finally, write the message length in the reserved place and restore the position
        this.pos = startPos - 1;
        this.writeVarint(len);
        this.pos += len;
    },

    writeFloat: function(val) {
        this.realloc(4);
        ieee754.write(this.buf, val, this.pos, true, 23, 4);
        this.pos += 4;
    },

    writeDouble: function(val) {
        this.realloc(8);
        ieee754.write(this.buf, val, this.pos, true, 52, 8);
        this.pos += 8;
    },

    writeBytes: function(buffer) {
        var len = buffer.length;
        this.writeVarint(len);
        this.realloc(len);
        for (var i = 0; i < len; i++) this.buf[this.pos++] = buffer[i];
    },

    writeRawMessage: function(fn, obj) {
        this.pos++; // reserve 1 byte for short message length

        // write the message directly to the buffer and see how much was written
        var startPos = this.pos;
        fn(obj, this);
        var len = this.pos - startPos;

        if (len >= 0x80) makeRoomForExtraLength(startPos, len, this);

        // finally, write the message length in the reserved place and restore the position
        this.pos = startPos - 1;
        this.writeVarint(len);
        this.pos += len;
    },

    writeMessage: function(tag, fn, obj) {
        this.writeTag(tag, Pbf.Bytes);
        this.writeRawMessage(fn, obj);
    },

    writePackedVarint:   function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedVarint, arr);   },
    writePackedSVarint:  function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedSVarint, arr);  },
    writePackedBoolean:  function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedBoolean, arr);  },
    writePackedFloat:    function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedFloat, arr);    },
    writePackedDouble:   function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedDouble, arr);   },
    writePackedFixed32:  function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedFixed32, arr);  },
    writePackedSFixed32: function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedSFixed32, arr); },
    writePackedFixed64:  function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedFixed64, arr);  },
    writePackedSFixed64: function(tag, arr) { if (arr.length) this.writeMessage(tag, writePackedSFixed64, arr); },

    writeBytesField: function(tag, buffer) {
        this.writeTag(tag, Pbf.Bytes);
        this.writeBytes(buffer);
    },
    writeFixed32Field: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed32);
        this.writeFixed32(val);
    },
    writeSFixed32Field: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed32);
        this.writeSFixed32(val);
    },
    writeFixed64Field: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed64);
        this.writeFixed64(val);
    },
    writeSFixed64Field: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed64);
        this.writeSFixed64(val);
    },
    writeVarintField: function(tag, val) {
        this.writeTag(tag, Pbf.Varint);
        this.writeVarint(val);
    },
    writeSVarintField: function(tag, val) {
        this.writeTag(tag, Pbf.Varint);
        this.writeSVarint(val);
    },
    writeStringField: function(tag, str) {
        this.writeTag(tag, Pbf.Bytes);
        this.writeString(str);
    },
    writeFloatField: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed32);
        this.writeFloat(val);
    },
    writeDoubleField: function(tag, val) {
        this.writeTag(tag, Pbf.Fixed64);
        this.writeDouble(val);
    },
    writeBooleanField: function(tag, val) {
        this.writeVarintField(tag, Boolean(val));
    }
};

function readVarintRemainder(l, s, p) {
    var buf = p.buf,
        h, b;

    b = buf[p.pos++]; h  = (b & 0x70) >> 4;  if (b < 0x80) return toNum(l, h, s);
    b = buf[p.pos++]; h |= (b & 0x7f) << 3;  if (b < 0x80) return toNum(l, h, s);
    b = buf[p.pos++]; h |= (b & 0x7f) << 10; if (b < 0x80) return toNum(l, h, s);
    b = buf[p.pos++]; h |= (b & 0x7f) << 17; if (b < 0x80) return toNum(l, h, s);
    b = buf[p.pos++]; h |= (b & 0x7f) << 24; if (b < 0x80) return toNum(l, h, s);
    b = buf[p.pos++]; h |= (b & 0x01) << 31; if (b < 0x80) return toNum(l, h, s);

    throw new Error('Expected varint not more than 10 bytes');
}

function readPackedEnd(pbf) {
    return pbf.type === Pbf.Bytes ?
        pbf.readVarint() + pbf.pos : pbf.pos + 1;
}

function toNum(low, high, isSigned) {
    if (isSigned) {
        return high * 0x100000000 + (low >>> 0);
    }

    return ((high >>> 0) * 0x100000000) + (low >>> 0);
}

function writeBigVarint(val, pbf) {
    var low, high;

    if (val >= 0) {
        low  = (val % 0x100000000) | 0;
        high = (val / 0x100000000) | 0;
    } else {
        low  = ~(-val % 0x100000000);
        high = ~(-val / 0x100000000);

        if (low ^ 0xffffffff) {
            low = (low + 1) | 0;
        } else {
            low = 0;
            high = (high + 1) | 0;
        }
    }

    if (val >= 0x10000000000000000 || val < -0x10000000000000000) {
        throw new Error('Given varint doesn\'t fit into 10 bytes');
    }

    pbf.realloc(10);

    writeBigVarintLow(low, high, pbf);
    writeBigVarintHigh(high, pbf);
}

function writeBigVarintLow(low, high, pbf) {
    pbf.buf[pbf.pos++] = low & 0x7f | 0x80; low >>>= 7;
    pbf.buf[pbf.pos++] = low & 0x7f | 0x80; low >>>= 7;
    pbf.buf[pbf.pos++] = low & 0x7f | 0x80; low >>>= 7;
    pbf.buf[pbf.pos++] = low & 0x7f | 0x80; low >>>= 7;
    pbf.buf[pbf.pos]   = low & 0x7f;
}

function writeBigVarintHigh(high, pbf) {
    var lsb = (high & 0x07) << 4;

    pbf.buf[pbf.pos++] |= lsb         | ((high >>>= 3) ? 0x80 : 0); if (!high) return;
    pbf.buf[pbf.pos++]  = high & 0x7f | ((high >>>= 7) ? 0x80 : 0); if (!high) return;
    pbf.buf[pbf.pos++]  = high & 0x7f | ((high >>>= 7) ? 0x80 : 0); if (!high) return;
    pbf.buf[pbf.pos++]  = high & 0x7f | ((high >>>= 7) ? 0x80 : 0); if (!high) return;
    pbf.buf[pbf.pos++]  = high & 0x7f | ((high >>>= 7) ? 0x80 : 0); if (!high) return;
    pbf.buf[pbf.pos++]  = high & 0x7f;
}

function makeRoomForExtraLength(startPos, len, pbf) {
    var extraLen =
        len <= 0x3fff ? 1 :
        len <= 0x1fffff ? 2 :
        len <= 0xfffffff ? 3 : Math.floor(Math.log(len) / (Math.LN2 * 7));

    // if 1 byte isn't enough for encoding message length, shift the data to the right
    pbf.realloc(extraLen);
    for (var i = pbf.pos - 1; i >= startPos; i--) pbf.buf[i + extraLen] = pbf.buf[i];
}

function writePackedVarint(arr, pbf)   { for (var i = 0; i < arr.length; i++) pbf.writeVarint(arr[i]);   }
function writePackedSVarint(arr, pbf)  { for (var i = 0; i < arr.length; i++) pbf.writeSVarint(arr[i]);  }
function writePackedFloat(arr, pbf)    { for (var i = 0; i < arr.length; i++) pbf.writeFloat(arr[i]);    }
function writePackedDouble(arr, pbf)   { for (var i = 0; i < arr.length; i++) pbf.writeDouble(arr[i]);   }
function writePackedBoolean(arr, pbf)  { for (var i = 0; i < arr.length; i++) pbf.writeBoolean(arr[i]);  }
function writePackedFixed32(arr, pbf)  { for (var i = 0; i < arr.length; i++) pbf.writeFixed32(arr[i]);  }
function writePackedSFixed32(arr, pbf) { for (var i = 0; i < arr.length; i++) pbf.writeSFixed32(arr[i]); }
function writePackedFixed64(arr, pbf)  { for (var i = 0; i < arr.length; i++) pbf.writeFixed64(arr[i]);  }
function writePackedSFixed64(arr, pbf) { for (var i = 0; i < arr.length; i++) pbf.writeSFixed64(arr[i]); }

// Buffer code below from https://github.com/feross/buffer, MIT-licensed

function readUInt32(buf, pos) {
    return ((buf[pos]) |
        (buf[pos + 1] << 8) |
        (buf[pos + 2] << 16)) +
        (buf[pos + 3] * 0x1000000);
}

function writeInt32(buf, val, pos) {
    buf[pos] = val;
    buf[pos + 1] = (val >>> 8);
    buf[pos + 2] = (val >>> 16);
    buf[pos + 3] = (val >>> 24);
}

function readInt32(buf, pos) {
    return ((buf[pos]) |
        (buf[pos + 1] << 8) |
        (buf[pos + 2] << 16)) +
        (buf[pos + 3] << 24);
}

function readUtf8(buf, pos, end) {
    var str = '';
    var i = pos;

    while (i < end) {
        var b0 = buf[i];
        var c = null; // codepoint
        var bytesPerSequence =
            b0 > 0xEF ? 4 :
            b0 > 0xDF ? 3 :
            b0 > 0xBF ? 2 : 1;

        if (i + bytesPerSequence > end) break;

        var b1, b2, b3;

        if (bytesPerSequence === 1) {
            if (b0 < 0x80) {
                c = b0;
            }
        } else if (bytesPerSequence === 2) {
            b1 = buf[i + 1];
            if ((b1 & 0xC0) === 0x80) {
                c = (b0 & 0x1F) << 0x6 | (b1 & 0x3F);
                if (c <= 0x7F) {
                    c = null;
                }
            }
        } else if (bytesPerSequence === 3) {
            b1 = buf[i + 1];
            b2 = buf[i + 2];
            if ((b1 & 0xC0) === 0x80 && (b2 & 0xC0) === 0x80) {
                c = (b0 & 0xF) << 0xC | (b1 & 0x3F) << 0x6 | (b2 & 0x3F);
                if (c <= 0x7FF || (c >= 0xD800 && c <= 0xDFFF)) {
                    c = null;
                }
            }
        } else if (bytesPerSequence === 4) {
            b1 = buf[i + 1];
            b2 = buf[i + 2];
            b3 = buf[i + 3];
            if ((b1 & 0xC0) === 0x80 && (b2 & 0xC0) === 0x80 && (b3 & 0xC0) === 0x80) {
                c = (b0 & 0xF) << 0x12 | (b1 & 0x3F) << 0xC | (b2 & 0x3F) << 0x6 | (b3 & 0x3F);
                if (c <= 0xFFFF || c >= 0x110000) {
                    c = null;
                }
            }
        }

        if (c === null) {
            c = 0xFFFD;
            bytesPerSequence = 1;

        } else if (c > 0xFFFF) {
            c -= 0x10000;
            str += String.fromCharCode(c >>> 10 & 0x3FF | 0xD800);
            c = 0xDC00 | c & 0x3FF;
        }

        str += String.fromCharCode(c);
        i += bytesPerSequence;
    }

    return str;
}

function readUtf8TextDecoder(buf, pos, end) {
    return utf8TextDecoder.decode(buf.subarray(pos, end));
}

function writeUtf8(buf, str, pos) {
    for (var i = 0, c, lead; i < str.length; i++) {
        c = str.charCodeAt(i); // code point

        if (c > 0xD7FF && c < 0xE000) {
            if (lead) {
                if (c < 0xDC00) {
                    buf[pos++] = 0xEF;
                    buf[pos++] = 0xBF;
                    buf[pos++] = 0xBD;
                    lead = c;
                    continue;
                } else {
                    c = lead - 0xD800 << 10 | c - 0xDC00 | 0x10000;
                    lead = null;
                }
            } else {
                if (c > 0xDBFF || (i + 1 === str.length)) {
                    buf[pos++] = 0xEF;
                    buf[pos++] = 0xBF;
                    buf[pos++] = 0xBD;
                } else {
                    lead = c;
                }
                continue;
            }
        } else if (lead) {
            buf[pos++] = 0xEF;
            buf[pos++] = 0xBF;
            buf[pos++] = 0xBD;
            lead = null;
        }

        if (c < 0x80) {
            buf[pos++] = c;
        } else {
            if (c < 0x800) {
                buf[pos++] = c >> 0x6 | 0xC0;
            } else {
                if (c < 0x10000) {
                    buf[pos++] = c >> 0xC | 0xE0;
                } else {
                    buf[pos++] = c >> 0x12 | 0xF0;
                    buf[pos++] = c >> 0xC & 0x3F | 0x80;
                }
                buf[pos++] = c >> 0x6 & 0x3F | 0x80;
            }
            buf[pos++] = c & 0x3F | 0x80;
        }
    }
    return pos;
}

},{"ieee754":7}],9:[function(require,module,exports){
"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
exports.__esModule = true;
exports.toOffset = exports.find = exports.init = void 0;
var geobuf_1 = require("geobuf");
var boolean_point_in_polygon_1 = __importDefault(require("@turf/boolean-point-in-polygon"));
var helpers_1 = require("@turf/helpers");
var pbf_1 = __importDefault(require("pbf"));
var oceanUtils_1 = require("./oceanUtils");
/**
 * Initialize the GeoTZ module with the given data sources.
 *
 * @param geoDataSource A string of the URL of the GeoJSON data or a function that returns an ArrayBuffer given a byte range.
 * @param tzDataSource A string of the URL of the index.json data or a function that returns an object.
 * @returns An object with a find function that can be used to find the timezone ID(s) at the given GPS coordinates.
 */
function init(geoDataSource, tzDataSource) {
    var _this = this;
    if (geoDataSource === void 0) { geoDataSource = "https://cdn.jsdelivr.net/npm/geo-tz@latest/data/timezones-1970.geojson.geo.dat"; }
    if (tzDataSource === void 0) { tzDataSource = "https://cdn.jsdelivr.net/npm/geo-tz@latest/data/timezones-1970.geojson.index.json"; }
    var geoData = typeof geoDataSource === "string"
        ? function (start, end) { return __awaiter(_this, void 0, void 0, function () {
            var response;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, fetch(geoDataSource, {
                            headers: { Range: "bytes=".concat(start, "-").concat(end) }
                        })];
                    case 1:
                        response = _a.sent();
                        return [4 /*yield*/, response.arrayBuffer()];
                    case 2: return [2 /*return*/, _a.sent()];
                }
            });
        }); }
        : geoDataSource;
    var tzDataPromise = null;
    var tzData = typeof tzDataSource === "string"
        ? function () { return __awaiter(_this, void 0, void 0, function () {
            var promise;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        if (!tzDataPromise) return [3 /*break*/, 2];
                        return [4 /*yield*/, tzDataPromise];
                    case 1: return [2 /*return*/, _a.sent()];
                    case 2:
                        promise = fetch(tzDataSource).then(function (response) {
                            return response.json();
                        });
                        tzDataPromise = promise;
                        return [4 /*yield*/, promise];
                    case 3: return [2 /*return*/, _a.sent()];
                }
            });
        }); }
        : tzDataSource;
    return {
        /**
         * Find the timezone ID(s) at the given GPS coordinates.
         *
         * @param lat latitude (must be >= -90 and <=90)
         * @param lon longitue (must be >= -180 and <=180)
         * @returns An array of string of TZIDs at the given coordinate.
         */
        find: function (lat, lon) { return __awaiter(_this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, findImpl(geoData, tzData, lat, lon)];
                    case 1: return [2 /*return*/, _a.sent()];
                }
            });
        }); }
    };
}
exports.init = init;
/**
 * Find the timezone ID(s) at the given GPS coordinates. This is identical to calling
 * `init()` and then calling `find()`.
 *
 * @param lat latitude (must be >= -90 and <=90)
 * @param lon longitue (must be >= -180 and <=180)
 * @returns An array of string of TZIDs at the given coordinate.
 */
function find(lat, lon) {
    return __awaiter(this, void 0, void 0, function () {
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: return [4 /*yield*/, init().find(lat, lon)];
                case 1: return [2 /*return*/, _a.sent()];
            }
        });
    });
}
exports.find = find;
function findImpl(geoData, tzData, lat, lon) {
    return __awaiter(this, void 0, void 0, function () {
        var originalLon, err, pt, quadData, quadPos, tzDataResponse, curTzData, _loop_1, state_1;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    originalLon = lon;
                    // validate latitude
                    if (isNaN(lat) || lat > 90 || lat < -90) {
                        err = new Error("Invalid latitude: " + lat);
                        throw err;
                    }
                    // validate longitude
                    if (isNaN(lon) || lon > 180 || lon < -180) {
                        err = new Error("Invalid longitude: " + lon);
                        throw err;
                    }
                    // North Pole should return all ocean zones
                    if (lat === 90) {
                        return [2 /*return*/, oceanUtils_1.oceanZones.map(function (zone) { return zone.tzid; })];
                    }
                    // fix edges of the world
                    if (lat >= 89.9999) {
                        lat = 89.9999;
                    }
                    else if (lat <= -89.9999) {
                        lat = -89.9999;
                    }
                    if (lon >= 179.9999) {
                        lon = 179.9999;
                    }
                    else if (lon <= -179.9999) {
                        lon = -179.9999;
                    }
                    pt = (0, helpers_1.point)([lon, lat]);
                    quadData = {
                        top: 89.9999,
                        bottom: -89.9999,
                        left: -179.9999,
                        right: 179.9999,
                        midLat: 0,
                        midLon: 0
                    };
                    quadPos = "";
                    return [4 /*yield*/, tzData()];
                case 1:
                    tzDataResponse = _a.sent();
                    curTzData = tzDataResponse.lookup;
                    _loop_1 = function () {
                        var nextQuad, bufSlice, geoJson, timezonesContainingPoint, i, timezones_1;
                        return __generator(this, function (_b) {
                            switch (_b.label) {
                                case 0:
                                    nextQuad = void 0;
                                    if (lat >= quadData.midLat && lon >= quadData.midLon) {
                                        nextQuad = "a";
                                        quadData.bottom = quadData.midLat;
                                        quadData.left = quadData.midLon;
                                    }
                                    else if (lat >= quadData.midLat && lon < quadData.midLon) {
                                        nextQuad = "b";
                                        quadData.bottom = quadData.midLat;
                                        quadData.right = quadData.midLon;
                                    }
                                    else if (lat < quadData.midLat && lon < quadData.midLon) {
                                        nextQuad = "c";
                                        quadData.top = quadData.midLat;
                                        quadData.right = quadData.midLon;
                                    }
                                    else {
                                        nextQuad = "d";
                                        quadData.top = quadData.midLat;
                                        quadData.left = quadData.midLon;
                                    }
                                    // console.log(nextQuad)
                                    curTzData = curTzData[nextQuad];
                                    // console.log()
                                    quadPos += nextQuad;
                                    if (!!curTzData) return [3 /*break*/, 1];
                                    return [2 /*return*/, { value: (0, oceanUtils_1.getTimezoneAtSea)(originalLon) }];
                                case 1:
                                    if (!(curTzData.pos >= 0 && curTzData.len)) return [3 /*break*/, 3];
                                    return [4 /*yield*/, geoData(curTzData.pos, curTzData.pos + curTzData.len - 1)];
                                case 2:
                                    bufSlice = _b.sent();
                                    geoJson = (0, geobuf_1.decode)(new pbf_1["default"](bufSlice));
                                    timezonesContainingPoint = [];
                                    if (geoJson.type === "FeatureCollection") {
                                        for (i = 0; i < geoJson.features.length; i++) {
                                            if ((0, boolean_point_in_polygon_1["default"])(pt, geoJson.features[i])) {
                                                timezonesContainingPoint.push(geoJson.features[i].properties.tzid);
                                            }
                                        }
                                    }
                                    else if (geoJson.type === "Feature") {
                                        if ((0, boolean_point_in_polygon_1["default"])(pt, geoJson)) {
                                            timezonesContainingPoint.push(geoJson.properties.tzid);
                                        }
                                    }
                                    return [2 /*return*/, { value: timezonesContainingPoint.length > 0
                                                ? timezonesContainingPoint
                                                : (0, oceanUtils_1.getTimezoneAtSea)(originalLon) }];
                                case 3:
                                    if (curTzData.length > 0) {
                                        timezones_1 = tzDataResponse.timezones;
                                        return [2 /*return*/, { value: curTzData.map(function (idx) { return timezones_1[idx]; }) }];
                                    }
                                    else if (typeof curTzData !== "object") {
                                        // not another nested quad index, throw error
                                        err = new Error("Unexpected data type");
                                        throw err;
                                    }
                                    _b.label = 4;
                                case 4:
                                    // calculate next quadtree depth data
                                    quadData.midLat = (quadData.top + quadData.bottom) / 2;
                                    quadData.midLon = (quadData.left + quadData.right) / 2;
                                    return [2 /*return*/];
                            }
                        });
                    };
                    _a.label = 2;
                case 2:
                    if (!true) return [3 /*break*/, 4];
                    return [5 /*yield**/, _loop_1()];
                case 3:
                    state_1 = _a.sent();
                    if (typeof state_1 === "object")
                        return [2 /*return*/, state_1.value];
                    return [3 /*break*/, 2];
                case 4: return [2 /*return*/];
            }
        });
    });
}
function toOffset(timeZone) {
    var date = new Date();
    var utcDate = new Date(date.toLocaleString("en-US", { timeZone: "UTC" }));
    var tzDate = new Date(date.toLocaleString("en-US", { timeZone: timeZone }));
    return (tzDate.getTime() - utcDate.getTime()) / 6e4;
}
exports.toOffset = toOffset;

},{"./oceanUtils":10,"@turf/boolean-point-in-polygon":1,"@turf/helpers":2,"geobuf":6,"pbf":8}],10:[function(require,module,exports){
"use strict";
exports.__esModule = true;
exports.getTimezoneAtSea = exports.oceanZones = void 0;
exports.oceanZones = [
    { tzid: 'Etc/GMT-12', left: 172.5, right: 180 },
    { tzid: 'Etc/GMT-11', left: 157.5, right: 172.5 },
    { tzid: 'Etc/GMT-10', left: 142.5, right: 157.5 },
    { tzid: 'Etc/GMT-9', left: 127.5, right: 142.5 },
    { tzid: 'Etc/GMT-8', left: 112.5, right: 127.5 },
    { tzid: 'Etc/GMT-7', left: 97.5, right: 112.5 },
    { tzid: 'Etc/GMT-6', left: 82.5, right: 97.5 },
    { tzid: 'Etc/GMT-5', left: 67.5, right: 82.5 },
    { tzid: 'Etc/GMT-4', left: 52.5, right: 67.5 },
    { tzid: 'Etc/GMT-3', left: 37.5, right: 52.5 },
    { tzid: 'Etc/GMT-2', left: 22.5, right: 37.5 },
    { tzid: 'Etc/GMT-1', left: 7.5, right: 22.5 },
    { tzid: 'Etc/GMT', left: -7.5, right: 7.5 },
    { tzid: 'Etc/GMT+1', left: -22.5, right: -7.5 },
    { tzid: 'Etc/GMT+2', left: -37.5, right: -22.5 },
    { tzid: 'Etc/GMT+3', left: -52.5, right: -37.5 },
    { tzid: 'Etc/GMT+4', left: -67.5, right: -52.5 },
    { tzid: 'Etc/GMT+5', left: -82.5, right: -67.5 },
    { tzid: 'Etc/GMT+6', left: -97.5, right: -82.5 },
    { tzid: 'Etc/GMT+7', left: -112.5, right: -97.5 },
    { tzid: 'Etc/GMT+8', left: -127.5, right: -112.5 },
    { tzid: 'Etc/GMT+9', left: -142.5, right: -127.5 },
    { tzid: 'Etc/GMT+10', left: -157.5, right: -142.5 },
    { tzid: 'Etc/GMT+11', left: -172.5, right: -157.5 },
    { tzid: 'Etc/GMT+12', left: -180, right: -172.5 },
];
/**
 * Find the Etc/GMT* timezone name(s) corresponding to the given longitue.
 *
 * @param lon The longitude to analyze
 * @returns An array of strings of TZIDs
 */
function getTimezoneAtSea(lon) {
    // coordinates along the 180 longitude should return two zones
    if (lon === -180 || lon === 180) {
        return ['Etc/GMT+12', 'Etc/GMT-12'];
    }
    var tzs = [];
    for (var i = 0; i < exports.oceanZones.length; i++) {
        var z = exports.oceanZones[i];
        if (z.left <= lon && z.right >= lon) {
            tzs.push(z.tzid);
        }
        else if (z.right < lon) {
            break;
        }
    }
    return tzs;
}
exports.getTimezoneAtSea = getTimezoneAtSea;

},{}]},{},[9])(9)
});

//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJub2RlX21vZHVsZXMvQHR1cmYvYm9vbGVhbi1wb2ludC1pbi1wb2x5Z29uL2Rpc3QvanMvaW5kZXguanMiLCJub2RlX21vZHVsZXMvQHR1cmYvaGVscGVycy9kaXN0L2pzL2luZGV4LmpzIiwibm9kZV9tb2R1bGVzL0B0dXJmL2ludmFyaWFudC9kaXN0L2pzL2luZGV4LmpzIiwibm9kZV9tb2R1bGVzL2dlb2J1Zi9kZWNvZGUuanMiLCJub2RlX21vZHVsZXMvZ2VvYnVmL2VuY29kZS5qcyIsIm5vZGVfbW9kdWxlcy9nZW9idWYvaW5kZXguanMiLCJub2RlX21vZHVsZXMvaWVlZTc1NC9pbmRleC5qcyIsIm5vZGVfbW9kdWxlcy9wYmYvaW5kZXguanMiLCJzcmMvZmluZC50cyIsInNyYy9vY2VhblV0aWxzLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FDQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN0SEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNwdEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN6T0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDOUtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDck9BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDSkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNyRkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDbG9CQSxpQ0FBZ0M7QUFDaEMsNEZBQW9EO0FBQ3BELHlDQUFzQztBQUN0Qyw0Q0FBc0I7QUFFdEIsMkNBQTREO0FBTzVEOzs7Ozs7R0FNRztBQUNILFNBQWdCLElBQUksQ0FDbEIsYUFBK0csRUFDL0csWUFBZ0g7SUFGbEgsaUJBMENDO0lBekNDLDhCQUFBLEVBQUEsZ0dBQStHO0lBQy9HLDZCQUFBLEVBQUEsa0dBQWdIO0lBRWhILElBQU0sT0FBTyxHQUNYLE9BQU8sYUFBYSxLQUFLLFFBQVE7UUFDL0IsQ0FBQyxDQUFDLFVBQU8sS0FBYSxFQUFFLEdBQVc7Ozs7NEJBQ2QscUJBQU0sS0FBSyxDQUFDLGFBQWEsRUFBRTs0QkFDMUMsT0FBTyxFQUFFLEVBQUUsS0FBSyxFQUFFLGdCQUFTLEtBQUssY0FBSSxHQUFHLENBQUUsRUFBRTt5QkFDNUMsQ0FBQyxFQUFBOzt3QkFGSSxRQUFRLEdBQUcsU0FFZjt3QkFDSyxxQkFBTSxRQUFRLENBQUMsV0FBVyxFQUFFLEVBQUE7NEJBQW5DLHNCQUFPLFNBQTRCLEVBQUM7OzthQUNyQztRQUNILENBQUMsQ0FBQyxhQUFhLENBQUM7SUFFcEIsSUFBSSxhQUFhLEdBQXdCLElBQUksQ0FBQztJQUU5QyxJQUFNLE1BQU0sR0FDVixPQUFPLFlBQVksS0FBSyxRQUFRO1FBQzlCLENBQUMsQ0FBQzs7Ozs7NkJBQ00sYUFBYSxFQUFiLHdCQUFhO3dCQUNSLHFCQUFNLGFBQWEsRUFBQTs0QkFBMUIsc0JBQU8sU0FBbUIsRUFBQzs7d0JBRXZCLE9BQU8sR0FBRyxLQUFLLENBQUMsWUFBWSxDQUFDLENBQUMsSUFBSSxDQUFDLFVBQUMsUUFBUTs0QkFDaEQsT0FBQSxRQUFRLENBQUMsSUFBSSxFQUFFO3dCQUFmLENBQWUsQ0FDaEIsQ0FBQzt3QkFDRixhQUFhLEdBQUcsT0FBTyxDQUFDO3dCQUNqQixxQkFBTSxPQUFPLEVBQUE7NEJBQXBCLHNCQUFPLFNBQWEsRUFBQzs7O2FBQ3RCO1FBQ0gsQ0FBQyxDQUFDLFlBQVksQ0FBQztJQUVuQixPQUFPO1FBQ0w7Ozs7OztXQU1HO1FBQ0gsSUFBSSxFQUFFLFVBQU8sR0FBVyxFQUFFLEdBQVc7Ozs0QkFDNUIscUJBQU0sUUFBUSxDQUFDLE9BQU8sRUFBRSxNQUFNLEVBQUUsR0FBRyxFQUFFLEdBQUcsQ0FBQyxFQUFBOzRCQUFoRCxzQkFBTyxTQUF5QyxFQUFDOzs7YUFDbEQ7S0FDRixDQUFDO0FBQ0osQ0FBQztBQTFDRCxvQkEwQ0M7QUFFRDs7Ozs7OztHQU9HO0FBQ0gsU0FBc0IsSUFBSSxDQUFDLEdBQVcsRUFBRSxHQUFXOzs7O3dCQUMxQyxxQkFBTSxJQUFJLEVBQUUsQ0FBQyxJQUFJLENBQUMsR0FBRyxFQUFFLEdBQUcsQ0FBQyxFQUFBO3dCQUFsQyxzQkFBTyxTQUEyQixFQUFDOzs7O0NBQ3BDO0FBRkQsb0JBRUM7QUFFRCxTQUFlLFFBQVEsQ0FDckIsT0FBNkQsRUFDN0QsTUFBMEIsRUFDMUIsR0FBVyxFQUNYLEdBQVc7Ozs7OztvQkFFTCxXQUFXLEdBQUcsR0FBRyxDQUFDO29CQUl4QixvQkFBb0I7b0JBQ3BCLElBQUksS0FBSyxDQUFDLEdBQUcsQ0FBQyxJQUFJLEdBQUcsR0FBRyxFQUFFLElBQUksR0FBRyxHQUFHLENBQUMsRUFBRSxFQUFFO3dCQUN2QyxHQUFHLEdBQUcsSUFBSSxLQUFLLENBQUMsb0JBQW9CLEdBQUcsR0FBRyxDQUFDLENBQUM7d0JBQzVDLE1BQU0sR0FBRyxDQUFDO3FCQUNYO29CQUVELHFCQUFxQjtvQkFDckIsSUFBSSxLQUFLLENBQUMsR0FBRyxDQUFDLElBQUksR0FBRyxHQUFHLEdBQUcsSUFBSSxHQUFHLEdBQUcsQ0FBQyxHQUFHLEVBQUU7d0JBQ3pDLEdBQUcsR0FBRyxJQUFJLEtBQUssQ0FBQyxxQkFBcUIsR0FBRyxHQUFHLENBQUMsQ0FBQzt3QkFDN0MsTUFBTSxHQUFHLENBQUM7cUJBQ1g7b0JBRUQsMkNBQTJDO29CQUMzQyxJQUFJLEdBQUcsS0FBSyxFQUFFLEVBQUU7d0JBQ2Qsc0JBQU8sdUJBQVUsQ0FBQyxHQUFHLENBQUMsVUFBQyxJQUFJLElBQUssT0FBQSxJQUFJLENBQUMsSUFBSSxFQUFULENBQVMsQ0FBQyxFQUFDO3FCQUM1QztvQkFFRCx5QkFBeUI7b0JBQ3pCLElBQUksR0FBRyxJQUFJLE9BQU8sRUFBRTt3QkFDbEIsR0FBRyxHQUFHLE9BQU8sQ0FBQztxQkFDZjt5QkFBTSxJQUFJLEdBQUcsSUFBSSxDQUFDLE9BQU8sRUFBRTt3QkFDMUIsR0FBRyxHQUFHLENBQUMsT0FBTyxDQUFDO3FCQUNoQjtvQkFFRCxJQUFJLEdBQUcsSUFBSSxRQUFRLEVBQUU7d0JBQ25CLEdBQUcsR0FBRyxRQUFRLENBQUM7cUJBQ2hCO3lCQUFNLElBQUksR0FBRyxJQUFJLENBQUMsUUFBUSxFQUFFO3dCQUMzQixHQUFHLEdBQUcsQ0FBQyxRQUFRLENBQUM7cUJBQ2pCO29CQUVLLEVBQUUsR0FBRyxJQUFBLGVBQUssRUFBQyxDQUFDLEdBQUcsRUFBRSxHQUFHLENBQUMsQ0FBQyxDQUFDO29CQUd2QixRQUFRLEdBQUc7d0JBQ2YsR0FBRyxFQUFFLE9BQU87d0JBQ1osTUFBTSxFQUFFLENBQUMsT0FBTzt3QkFDaEIsSUFBSSxFQUFFLENBQUMsUUFBUTt3QkFDZixLQUFLLEVBQUUsUUFBUTt3QkFDZixNQUFNLEVBQUUsQ0FBQzt3QkFDVCxNQUFNLEVBQUUsQ0FBQztxQkFDVixDQUFDO29CQUNFLE9BQU8sR0FBRyxFQUFFLENBQUM7b0JBRU0scUJBQU0sTUFBTSxFQUFFLEVBQUE7O29CQUEvQixjQUFjLEdBQUcsU0FBYztvQkFFakMsU0FBUyxHQUFHLGNBQWMsQ0FBQyxNQUFNLENBQUM7Ozs7OztvQ0FJaEMsUUFBUSxTQUFBLENBQUM7b0NBQ2IsSUFBSSxHQUFHLElBQUksUUFBUSxDQUFDLE1BQU0sSUFBSSxHQUFHLElBQUksUUFBUSxDQUFDLE1BQU0sRUFBRTt3Q0FDcEQsUUFBUSxHQUFHLEdBQUcsQ0FBQzt3Q0FDZixRQUFRLENBQUMsTUFBTSxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUM7d0NBQ2xDLFFBQVEsQ0FBQyxJQUFJLEdBQUcsUUFBUSxDQUFDLE1BQU0sQ0FBQztxQ0FDakM7eUNBQU0sSUFBSSxHQUFHLElBQUksUUFBUSxDQUFDLE1BQU0sSUFBSSxHQUFHLEdBQUcsUUFBUSxDQUFDLE1BQU0sRUFBRTt3Q0FDMUQsUUFBUSxHQUFHLEdBQUcsQ0FBQzt3Q0FDZixRQUFRLENBQUMsTUFBTSxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUM7d0NBQ2xDLFFBQVEsQ0FBQyxLQUFLLEdBQUcsUUFBUSxDQUFDLE1BQU0sQ0FBQztxQ0FDbEM7eUNBQU0sSUFBSSxHQUFHLEdBQUcsUUFBUSxDQUFDLE1BQU0sSUFBSSxHQUFHLEdBQUcsUUFBUSxDQUFDLE1BQU0sRUFBRTt3Q0FDekQsUUFBUSxHQUFHLEdBQUcsQ0FBQzt3Q0FDZixRQUFRLENBQUMsR0FBRyxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUM7d0NBQy9CLFFBQVEsQ0FBQyxLQUFLLEdBQUcsUUFBUSxDQUFDLE1BQU0sQ0FBQztxQ0FDbEM7eUNBQU07d0NBQ0wsUUFBUSxHQUFHLEdBQUcsQ0FBQzt3Q0FDZixRQUFRLENBQUMsR0FBRyxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUM7d0NBQy9CLFFBQVEsQ0FBQyxJQUFJLEdBQUcsUUFBUSxDQUFDLE1BQU0sQ0FBQztxQ0FDakM7b0NBRUQsd0JBQXdCO29DQUN4QixTQUFTLEdBQUcsU0FBUyxDQUFDLFFBQVEsQ0FBQyxDQUFDO29DQUNoQyxnQkFBZ0I7b0NBQ2hCLE9BQU8sSUFBSSxRQUFRLENBQUM7eUNBR2hCLENBQUMsU0FBUyxFQUFWLHdCQUFVO21FQUVMLElBQUEsNkJBQWdCLEVBQUMsV0FBVyxDQUFDOzt5Q0FDM0IsQ0FBQSxTQUFTLENBQUMsR0FBRyxJQUFJLENBQUMsSUFBSSxTQUFTLENBQUMsR0FBRyxDQUFBLEVBQW5DLHdCQUFtQztvQ0FFM0IscUJBQU0sT0FBTyxDQUM1QixTQUFTLENBQUMsR0FBRyxFQUNiLFNBQVMsQ0FBQyxHQUFHLEdBQUcsU0FBUyxDQUFDLEdBQUcsR0FBRyxDQUFDLENBQ2xDLEVBQUE7O29DQUhLLFFBQVEsR0FBRyxTQUdoQjtvQ0FDSyxPQUFPLEdBQUcsSUFBQSxlQUFNLEVBQUMsSUFBSSxnQkFBRyxDQUFDLFFBQVEsQ0FBQyxDQUFDLENBQUM7b0NBRXBDLHdCQUF3QixHQUFHLEVBQUUsQ0FBQztvQ0FFcEMsSUFBSSxPQUFPLENBQUMsSUFBSSxLQUFLLG1CQUFtQixFQUFFO3dDQUN4QyxLQUFTLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLE9BQU8sQ0FBQyxRQUFRLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFOzRDQUNoRCxJQUFJLElBQUEscUNBQU0sRUFBQyxFQUFFLEVBQUUsT0FBTyxDQUFDLFFBQVEsQ0FBQyxDQUFDLENBQVEsQ0FBQyxFQUFFO2dEQUMxQyx3QkFBd0IsQ0FBQyxJQUFJLENBQUMsT0FBTyxDQUFDLFFBQVEsQ0FBQyxDQUFDLENBQUMsQ0FBQyxVQUFVLENBQUMsSUFBSSxDQUFDLENBQUM7NkNBQ3BFO3lDQUNGO3FDQUNGO3lDQUFNLElBQUksT0FBTyxDQUFDLElBQUksS0FBSyxTQUFTLEVBQUU7d0NBQ3JDLElBQUksSUFBQSxxQ0FBTSxFQUFDLEVBQUUsRUFBRSxPQUFjLENBQUMsRUFBRTs0Q0FDOUIsd0JBQXdCLENBQUMsSUFBSSxDQUFDLE9BQU8sQ0FBQyxVQUFVLENBQUMsSUFBSSxDQUFDLENBQUM7eUNBQ3hEO3FDQUNGO21FQUlNLHdCQUF3QixDQUFDLE1BQU0sR0FBRyxDQUFDO2dEQUN4QyxDQUFDLENBQUMsd0JBQXdCO2dEQUMxQixDQUFDLENBQUMsSUFBQSw2QkFBZ0IsRUFBQyxXQUFXLENBQUM7O29DQUM1QixJQUFJLFNBQVMsQ0FBQyxNQUFNLEdBQUcsQ0FBQyxFQUFFO3dDQUV6QixjQUFZLGNBQWMsQ0FBQyxTQUFTLENBQUM7dUVBQ3BDLFNBQVMsQ0FBQyxHQUFHLENBQUMsVUFBQyxHQUFHLElBQUssT0FBQSxXQUFTLENBQUMsR0FBRyxDQUFDLEVBQWQsQ0FBYyxDQUFDO3FDQUM5Qzt5Q0FBTSxJQUFJLE9BQU8sU0FBUyxLQUFLLFFBQVEsRUFBRTt3Q0FDeEMsNkNBQTZDO3dDQUM3QyxHQUFHLEdBQUcsSUFBSSxLQUFLLENBQUMsc0JBQXNCLENBQUMsQ0FBQzt3Q0FDeEMsTUFBTSxHQUFHLENBQUM7cUNBQ1g7OztvQ0FFRCxxQ0FBcUM7b0NBQ3JDLFFBQVEsQ0FBQyxNQUFNLEdBQUcsQ0FBQyxRQUFRLENBQUMsR0FBRyxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUMsR0FBRyxDQUFDLENBQUM7b0NBQ3ZELFFBQVEsQ0FBQyxNQUFNLEdBQUcsQ0FBQyxRQUFRLENBQUMsSUFBSSxHQUFHLFFBQVEsQ0FBQyxLQUFLLENBQUMsR0FBRyxDQUFDLENBQUM7Ozs7Ozs7eUJBckVsRCxJQUFJOzs7Ozs7Ozs7OztDQXVFWjtBQUVELFNBQWdCLFFBQVEsQ0FBQyxRQUFnQjtJQUN2QyxJQUFNLElBQUksR0FBRyxJQUFJLElBQUksRUFBRSxDQUFDO0lBQ3hCLElBQU0sT0FBTyxHQUFHLElBQUksSUFBSSxDQUFDLElBQUksQ0FBQyxjQUFjLENBQUMsT0FBTyxFQUFFLEVBQUUsUUFBUSxFQUFFLEtBQUssRUFBRSxDQUFDLENBQUMsQ0FBQztJQUM1RSxJQUFNLE1BQU0sR0FBRyxJQUFJLElBQUksQ0FBQyxJQUFJLENBQUMsY0FBYyxDQUFDLE9BQU8sRUFBRSxFQUFFLFFBQVEsVUFBQSxFQUFFLENBQUMsQ0FBQyxDQUFDO0lBQ3BFLE9BQU8sQ0FBQyxNQUFNLENBQUMsT0FBTyxFQUFFLEdBQUcsT0FBTyxDQUFDLE9BQU8sRUFBRSxDQUFDLEdBQUcsR0FBRyxDQUFDO0FBQ3RELENBQUM7QUFMRCw0QkFLQzs7Ozs7O0FDNU1ZLFFBQUEsVUFBVSxHQUFnQjtJQUNyQyxFQUFFLElBQUksRUFBRSxZQUFZLEVBQUUsSUFBSSxFQUFFLEtBQUssRUFBRSxLQUFLLEVBQUUsR0FBRyxFQUFFO0lBQy9DLEVBQUUsSUFBSSxFQUFFLFlBQVksRUFBRSxJQUFJLEVBQUUsS0FBSyxFQUFFLEtBQUssRUFBRSxLQUFLLEVBQUU7SUFDakQsRUFBRSxJQUFJLEVBQUUsWUFBWSxFQUFFLElBQUksRUFBRSxLQUFLLEVBQUUsS0FBSyxFQUFFLEtBQUssRUFBRTtJQUNqRCxFQUFFLElBQUksRUFBRSxXQUFXLEVBQUUsSUFBSSxFQUFFLEtBQUssRUFBRSxLQUFLLEVBQUUsS0FBSyxFQUFFO0lBQ2hELEVBQUUsSUFBSSxFQUFFLFdBQVcsRUFBRSxJQUFJLEVBQUUsS0FBSyxFQUFFLEtBQUssRUFBRSxLQUFLLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxJQUFJLEVBQUUsS0FBSyxFQUFFLEtBQUssRUFBRTtJQUMvQyxFQUFFLElBQUksRUFBRSxXQUFXLEVBQUUsSUFBSSxFQUFFLElBQUksRUFBRSxLQUFLLEVBQUUsSUFBSSxFQUFFO0lBQzlDLEVBQUUsSUFBSSxFQUFFLFdBQVcsRUFBRSxJQUFJLEVBQUUsSUFBSSxFQUFFLEtBQUssRUFBRSxJQUFJLEVBQUU7SUFDOUMsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxJQUFJLEVBQUUsS0FBSyxFQUFFLElBQUksRUFBRTtJQUM5QyxFQUFFLElBQUksRUFBRSxXQUFXLEVBQUUsSUFBSSxFQUFFLElBQUksRUFBRSxLQUFLLEVBQUUsSUFBSSxFQUFFO0lBQzlDLEVBQUUsSUFBSSxFQUFFLFdBQVcsRUFBRSxJQUFJLEVBQUUsSUFBSSxFQUFFLEtBQUssRUFBRSxJQUFJLEVBQUU7SUFDOUMsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxHQUFHLEVBQUUsS0FBSyxFQUFFLElBQUksRUFBRTtJQUM3QyxFQUFFLElBQUksRUFBRSxTQUFTLEVBQUUsSUFBSSxFQUFFLENBQUMsR0FBRyxFQUFFLEtBQUssRUFBRSxHQUFHLEVBQUU7SUFDM0MsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxHQUFHLEVBQUU7SUFDL0MsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLElBQUksRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDaEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLEtBQUssRUFBRSxLQUFLLEVBQUUsQ0FBQyxJQUFJLEVBQUU7SUFDakQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLEtBQUssRUFBRSxLQUFLLEVBQUUsQ0FBQyxLQUFLLEVBQUU7SUFDbEQsRUFBRSxJQUFJLEVBQUUsV0FBVyxFQUFFLElBQUksRUFBRSxDQUFDLEtBQUssRUFBRSxLQUFLLEVBQUUsQ0FBQyxLQUFLLEVBQUU7SUFDbEQsRUFBRSxJQUFJLEVBQUUsWUFBWSxFQUFFLElBQUksRUFBRSxDQUFDLEtBQUssRUFBRSxLQUFLLEVBQUUsQ0FBQyxLQUFLLEVBQUU7SUFDbkQsRUFBRSxJQUFJLEVBQUUsWUFBWSxFQUFFLElBQUksRUFBRSxDQUFDLEtBQUssRUFBRSxLQUFLLEVBQUUsQ0FBQyxLQUFLLEVBQUU7SUFDbkQsRUFBRSxJQUFJLEVBQUUsWUFBWSxFQUFFLElBQUksRUFBRSxDQUFDLEdBQUcsRUFBRSxLQUFLLEVBQUUsQ0FBQyxLQUFLLEVBQUU7Q0FDbEQsQ0FBQTtBQUVEOzs7OztHQUtHO0FBQ0gsU0FBZ0IsZ0JBQWdCLENBQUMsR0FBVztJQUMxQyw4REFBOEQ7SUFDOUQsSUFBSSxHQUFHLEtBQUssQ0FBQyxHQUFHLElBQUksR0FBRyxLQUFLLEdBQUcsRUFBRTtRQUMvQixPQUFPLENBQUMsWUFBWSxFQUFFLFlBQVksQ0FBQyxDQUFBO0tBQ3BDO0lBQ0QsSUFBTSxHQUFHLEdBQUcsRUFBRSxDQUFBO0lBQ2QsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLGtCQUFVLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFO1FBQzFDLElBQU0sQ0FBQyxHQUFHLGtCQUFVLENBQUMsQ0FBQyxDQUFDLENBQUE7UUFDdkIsSUFBSSxDQUFDLENBQUMsSUFBSSxJQUFJLEdBQUcsSUFBSSxDQUFDLENBQUMsS0FBSyxJQUFJLEdBQUcsRUFBRTtZQUNuQyxHQUFHLENBQUMsSUFBSSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQTtTQUNqQjthQUFNLElBQUksQ0FBQyxDQUFDLEtBQUssR0FBRyxHQUFHLEVBQUU7WUFDeEIsTUFBSztTQUNOO0tBQ0Y7SUFDRCxPQUFPLEdBQUcsQ0FBQTtBQUNaLENBQUM7QUFmRCw0Q0FlQyIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uKCl7ZnVuY3Rpb24gcihlLG4sdCl7ZnVuY3Rpb24gbyhpLGYpe2lmKCFuW2ldKXtpZighZVtpXSl7dmFyIGM9XCJmdW5jdGlvblwiPT10eXBlb2YgcmVxdWlyZSYmcmVxdWlyZTtpZighZiYmYylyZXR1cm4gYyhpLCEwKTtpZih1KXJldHVybiB1KGksITApO3ZhciBhPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIraStcIidcIik7dGhyb3cgYS5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGF9dmFyIHA9bltpXT17ZXhwb3J0czp7fX07ZVtpXVswXS5jYWxsKHAuZXhwb3J0cyxmdW5jdGlvbihyKXt2YXIgbj1lW2ldWzFdW3JdO3JldHVybiBvKG58fHIpfSxwLHAuZXhwb3J0cyxyLGUsbix0KX1yZXR1cm4gbltpXS5leHBvcnRzfWZvcih2YXIgdT1cImZ1bmN0aW9uXCI9PXR5cGVvZiByZXF1aXJlJiZyZXF1aXJlLGk9MDtpPHQubGVuZ3RoO2krKylvKHRbaV0pO3JldHVybiBvfXJldHVybiByfSkoKSIsIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xudmFyIGludmFyaWFudF8xID0gcmVxdWlyZShcIkB0dXJmL2ludmFyaWFudFwiKTtcbi8vIGh0dHA6Ly9lbi53aWtpcGVkaWEub3JnL3dpa2kvRXZlbiVFMiU4MCU5M29kZF9ydWxlXG4vLyBtb2RpZmllZCBmcm9tOiBodHRwczovL2dpdGh1Yi5jb20vc3Vic3RhY2svcG9pbnQtaW4tcG9seWdvbi9ibG9iL21hc3Rlci9pbmRleC5qc1xuLy8gd2hpY2ggd2FzIG1vZGlmaWVkIGZyb20gaHR0cDovL3d3dy5lY3NlLnJwaS5lZHUvSG9tZXBhZ2VzL3dyZi9SZXNlYXJjaC9TaG9ydF9Ob3Rlcy9wbnBvbHkuaHRtbFxuLyoqXG4gKiBUYWtlcyBhIHtAbGluayBQb2ludH0gYW5kIGEge0BsaW5rIFBvbHlnb259IG9yIHtAbGluayBNdWx0aVBvbHlnb259IGFuZCBkZXRlcm1pbmVzIGlmIHRoZSBwb2ludFxuICogcmVzaWRlcyBpbnNpZGUgdGhlIHBvbHlnb24uIFRoZSBwb2x5Z29uIGNhbiBiZSBjb252ZXggb3IgY29uY2F2ZS4gVGhlIGZ1bmN0aW9uIGFjY291bnRzIGZvciBob2xlcy5cbiAqXG4gKiBAbmFtZSBib29sZWFuUG9pbnRJblBvbHlnb25cbiAqIEBwYXJhbSB7Q29vcmR9IHBvaW50IGlucHV0IHBvaW50XG4gKiBAcGFyYW0ge0ZlYXR1cmU8UG9seWdvbnxNdWx0aVBvbHlnb24+fSBwb2x5Z29uIGlucHV0IHBvbHlnb24gb3IgbXVsdGlwb2x5Z29uXG4gKiBAcGFyYW0ge09iamVjdH0gW29wdGlvbnM9e31dIE9wdGlvbmFsIHBhcmFtZXRlcnNcbiAqIEBwYXJhbSB7Ym9vbGVhbn0gW29wdGlvbnMuaWdub3JlQm91bmRhcnk9ZmFsc2VdIFRydWUgaWYgcG9seWdvbiBib3VuZGFyeSBzaG91bGQgYmUgaWdub3JlZCB3aGVuIGRldGVybWluaW5nIGlmXG4gKiB0aGUgcG9pbnQgaXMgaW5zaWRlIHRoZSBwb2x5Z29uIG90aGVyd2lzZSBmYWxzZS5cbiAqIEByZXR1cm5zIHtib29sZWFufSBgdHJ1ZWAgaWYgdGhlIFBvaW50IGlzIGluc2lkZSB0aGUgUG9seWdvbjsgYGZhbHNlYCBpZiB0aGUgUG9pbnQgaXMgbm90IGluc2lkZSB0aGUgUG9seWdvblxuICogQGV4YW1wbGVcbiAqIHZhciBwdCA9IHR1cmYucG9pbnQoWy03NywgNDRdKTtcbiAqIHZhciBwb2x5ID0gdHVyZi5wb2x5Z29uKFtbXG4gKiAgIFstODEsIDQxXSxcbiAqICAgWy04MSwgNDddLFxuICogICBbLTcyLCA0N10sXG4gKiAgIFstNzIsIDQxXSxcbiAqICAgWy04MSwgNDFdXG4gKiBdXSk7XG4gKlxuICogdHVyZi5ib29sZWFuUG9pbnRJblBvbHlnb24ocHQsIHBvbHkpO1xuICogLy89IHRydWVcbiAqL1xuZnVuY3Rpb24gYm9vbGVhblBvaW50SW5Qb2x5Z29uKHBvaW50LCBwb2x5Z29uLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICAvLyB2YWxpZGF0aW9uXG4gICAgaWYgKCFwb2ludCkge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJwb2ludCBpcyByZXF1aXJlZFwiKTtcbiAgICB9XG4gICAgaWYgKCFwb2x5Z29uKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcInBvbHlnb24gaXMgcmVxdWlyZWRcIik7XG4gICAgfVxuICAgIHZhciBwdCA9IGludmFyaWFudF8xLmdldENvb3JkKHBvaW50KTtcbiAgICB2YXIgZ2VvbSA9IGludmFyaWFudF8xLmdldEdlb20ocG9seWdvbik7XG4gICAgdmFyIHR5cGUgPSBnZW9tLnR5cGU7XG4gICAgdmFyIGJib3ggPSBwb2x5Z29uLmJib3g7XG4gICAgdmFyIHBvbHlzID0gZ2VvbS5jb29yZGluYXRlcztcbiAgICAvLyBRdWljayBlbGltaW5hdGlvbiBpZiBwb2ludCBpcyBub3QgaW5zaWRlIGJib3hcbiAgICBpZiAoYmJveCAmJiBpbkJCb3gocHQsIGJib3gpID09PSBmYWxzZSkge1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuICAgIC8vIG5vcm1hbGl6ZSB0byBtdWx0aXBvbHlnb25cbiAgICBpZiAodHlwZSA9PT0gXCJQb2x5Z29uXCIpIHtcbiAgICAgICAgcG9seXMgPSBbcG9seXNdO1xuICAgIH1cbiAgICB2YXIgaW5zaWRlUG9seSA9IGZhbHNlO1xuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgcG9seXMubGVuZ3RoICYmICFpbnNpZGVQb2x5OyBpKyspIHtcbiAgICAgICAgLy8gY2hlY2sgaWYgaXQgaXMgaW4gdGhlIG91dGVyIHJpbmcgZmlyc3RcbiAgICAgICAgaWYgKGluUmluZyhwdCwgcG9seXNbaV1bMF0sIG9wdGlvbnMuaWdub3JlQm91bmRhcnkpKSB7XG4gICAgICAgICAgICB2YXIgaW5Ib2xlID0gZmFsc2U7XG4gICAgICAgICAgICB2YXIgayA9IDE7XG4gICAgICAgICAgICAvLyBjaGVjayBmb3IgdGhlIHBvaW50IGluIGFueSBvZiB0aGUgaG9sZXNcbiAgICAgICAgICAgIHdoaWxlIChrIDwgcG9seXNbaV0ubGVuZ3RoICYmICFpbkhvbGUpIHtcbiAgICAgICAgICAgICAgICBpZiAoaW5SaW5nKHB0LCBwb2x5c1tpXVtrXSwgIW9wdGlvbnMuaWdub3JlQm91bmRhcnkpKSB7XG4gICAgICAgICAgICAgICAgICAgIGluSG9sZSA9IHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGsrKztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICghaW5Ib2xlKSB7XG4gICAgICAgICAgICAgICAgaW5zaWRlUG9seSA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIGluc2lkZVBvbHk7XG59XG5leHBvcnRzLmRlZmF1bHQgPSBib29sZWFuUG9pbnRJblBvbHlnb247XG4vKipcbiAqIGluUmluZ1xuICpcbiAqIEBwcml2YXRlXG4gKiBAcGFyYW0ge0FycmF5PG51bWJlcj59IHB0IFt4LHldXG4gKiBAcGFyYW0ge0FycmF5PEFycmF5PG51bWJlcj4+fSByaW5nIFtbeCx5XSwgW3gseV0sLi5dXG4gKiBAcGFyYW0ge2Jvb2xlYW59IGlnbm9yZUJvdW5kYXJ5IGlnbm9yZUJvdW5kYXJ5XG4gKiBAcmV0dXJucyB7Ym9vbGVhbn0gaW5SaW5nXG4gKi9cbmZ1bmN0aW9uIGluUmluZyhwdCwgcmluZywgaWdub3JlQm91bmRhcnkpIHtcbiAgICB2YXIgaXNJbnNpZGUgPSBmYWxzZTtcbiAgICBpZiAocmluZ1swXVswXSA9PT0gcmluZ1tyaW5nLmxlbmd0aCAtIDFdWzBdICYmXG4gICAgICAgIHJpbmdbMF1bMV0gPT09IHJpbmdbcmluZy5sZW5ndGggLSAxXVsxXSkge1xuICAgICAgICByaW5nID0gcmluZy5zbGljZSgwLCByaW5nLmxlbmd0aCAtIDEpO1xuICAgIH1cbiAgICBmb3IgKHZhciBpID0gMCwgaiA9IHJpbmcubGVuZ3RoIC0gMTsgaSA8IHJpbmcubGVuZ3RoOyBqID0gaSsrKSB7XG4gICAgICAgIHZhciB4aSA9IHJpbmdbaV1bMF07XG4gICAgICAgIHZhciB5aSA9IHJpbmdbaV1bMV07XG4gICAgICAgIHZhciB4aiA9IHJpbmdbal1bMF07XG4gICAgICAgIHZhciB5aiA9IHJpbmdbal1bMV07XG4gICAgICAgIHZhciBvbkJvdW5kYXJ5ID0gcHRbMV0gKiAoeGkgLSB4aikgKyB5aSAqICh4aiAtIHB0WzBdKSArIHlqICogKHB0WzBdIC0geGkpID09PSAwICYmXG4gICAgICAgICAgICAoeGkgLSBwdFswXSkgKiAoeGogLSBwdFswXSkgPD0gMCAmJlxuICAgICAgICAgICAgKHlpIC0gcHRbMV0pICogKHlqIC0gcHRbMV0pIDw9IDA7XG4gICAgICAgIGlmIChvbkJvdW5kYXJ5KSB7XG4gICAgICAgICAgICByZXR1cm4gIWlnbm9yZUJvdW5kYXJ5O1xuICAgICAgICB9XG4gICAgICAgIHZhciBpbnRlcnNlY3QgPSB5aSA+IHB0WzFdICE9PSB5aiA+IHB0WzFdICYmXG4gICAgICAgICAgICBwdFswXSA8ICgoeGogLSB4aSkgKiAocHRbMV0gLSB5aSkpIC8gKHlqIC0geWkpICsgeGk7XG4gICAgICAgIGlmIChpbnRlcnNlY3QpIHtcbiAgICAgICAgICAgIGlzSW5zaWRlID0gIWlzSW5zaWRlO1xuICAgICAgICB9XG4gICAgfVxuICAgIHJldHVybiBpc0luc2lkZTtcbn1cbi8qKlxuICogaW5CQm94XG4gKlxuICogQHByaXZhdGVcbiAqIEBwYXJhbSB7UG9zaXRpb259IHB0IHBvaW50IFt4LHldXG4gKiBAcGFyYW0ge0JCb3h9IGJib3ggQkJveCBbd2VzdCwgc291dGgsIGVhc3QsIG5vcnRoXVxuICogQHJldHVybnMge2Jvb2xlYW59IHRydWUvZmFsc2UgaWYgcG9pbnQgaXMgaW5zaWRlIEJCb3hcbiAqL1xuZnVuY3Rpb24gaW5CQm94KHB0LCBiYm94KSB7XG4gICAgcmV0dXJuIChiYm94WzBdIDw9IHB0WzBdICYmIGJib3hbMV0gPD0gcHRbMV0gJiYgYmJveFsyXSA+PSBwdFswXSAmJiBiYm94WzNdID49IHB0WzFdKTtcbn1cbiIsIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuLyoqXG4gKiBAbW9kdWxlIGhlbHBlcnNcbiAqL1xuLyoqXG4gKiBFYXJ0aCBSYWRpdXMgdXNlZCB3aXRoIHRoZSBIYXJ2ZXNpbmUgZm9ybXVsYSBhbmQgYXBwcm94aW1hdGVzIHVzaW5nIGEgc3BoZXJpY2FsIChub24tZWxsaXBzb2lkKSBFYXJ0aC5cbiAqXG4gKiBAbWVtYmVyb2YgaGVscGVyc1xuICogQHR5cGUge251bWJlcn1cbiAqL1xuZXhwb3J0cy5lYXJ0aFJhZGl1cyA9IDYzNzEwMDguODtcbi8qKlxuICogVW5pdCBvZiBtZWFzdXJlbWVudCBmYWN0b3JzIHVzaW5nIGEgc3BoZXJpY2FsIChub24tZWxsaXBzb2lkKSBlYXJ0aCByYWRpdXMuXG4gKlxuICogQG1lbWJlcm9mIGhlbHBlcnNcbiAqIEB0eXBlIHtPYmplY3R9XG4gKi9cbmV4cG9ydHMuZmFjdG9ycyA9IHtcbiAgICBjZW50aW1ldGVyczogZXhwb3J0cy5lYXJ0aFJhZGl1cyAqIDEwMCxcbiAgICBjZW50aW1ldHJlczogZXhwb3J0cy5lYXJ0aFJhZGl1cyAqIDEwMCxcbiAgICBkZWdyZWVzOiBleHBvcnRzLmVhcnRoUmFkaXVzIC8gMTExMzI1LFxuICAgIGZlZXQ6IGV4cG9ydHMuZWFydGhSYWRpdXMgKiAzLjI4MDg0LFxuICAgIGluY2hlczogZXhwb3J0cy5lYXJ0aFJhZGl1cyAqIDM5LjM3LFxuICAgIGtpbG9tZXRlcnM6IGV4cG9ydHMuZWFydGhSYWRpdXMgLyAxMDAwLFxuICAgIGtpbG9tZXRyZXM6IGV4cG9ydHMuZWFydGhSYWRpdXMgLyAxMDAwLFxuICAgIG1ldGVyczogZXhwb3J0cy5lYXJ0aFJhZGl1cyxcbiAgICBtZXRyZXM6IGV4cG9ydHMuZWFydGhSYWRpdXMsXG4gICAgbWlsZXM6IGV4cG9ydHMuZWFydGhSYWRpdXMgLyAxNjA5LjM0NCxcbiAgICBtaWxsaW1ldGVyczogZXhwb3J0cy5lYXJ0aFJhZGl1cyAqIDEwMDAsXG4gICAgbWlsbGltZXRyZXM6IGV4cG9ydHMuZWFydGhSYWRpdXMgKiAxMDAwLFxuICAgIG5hdXRpY2FsbWlsZXM6IGV4cG9ydHMuZWFydGhSYWRpdXMgLyAxODUyLFxuICAgIHJhZGlhbnM6IDEsXG4gICAgeWFyZHM6IGV4cG9ydHMuZWFydGhSYWRpdXMgKiAxLjA5MzYsXG59O1xuLyoqXG4gKiBVbml0cyBvZiBtZWFzdXJlbWVudCBmYWN0b3JzIGJhc2VkIG9uIDEgbWV0ZXIuXG4gKlxuICogQG1lbWJlcm9mIGhlbHBlcnNcbiAqIEB0eXBlIHtPYmplY3R9XG4gKi9cbmV4cG9ydHMudW5pdHNGYWN0b3JzID0ge1xuICAgIGNlbnRpbWV0ZXJzOiAxMDAsXG4gICAgY2VudGltZXRyZXM6IDEwMCxcbiAgICBkZWdyZWVzOiAxIC8gMTExMzI1LFxuICAgIGZlZXQ6IDMuMjgwODQsXG4gICAgaW5jaGVzOiAzOS4zNyxcbiAgICBraWxvbWV0ZXJzOiAxIC8gMTAwMCxcbiAgICBraWxvbWV0cmVzOiAxIC8gMTAwMCxcbiAgICBtZXRlcnM6IDEsXG4gICAgbWV0cmVzOiAxLFxuICAgIG1pbGVzOiAxIC8gMTYwOS4zNDQsXG4gICAgbWlsbGltZXRlcnM6IDEwMDAsXG4gICAgbWlsbGltZXRyZXM6IDEwMDAsXG4gICAgbmF1dGljYWxtaWxlczogMSAvIDE4NTIsXG4gICAgcmFkaWFuczogMSAvIGV4cG9ydHMuZWFydGhSYWRpdXMsXG4gICAgeWFyZHM6IDEuMDkzNjEzMyxcbn07XG4vKipcbiAqIEFyZWEgb2YgbWVhc3VyZW1lbnQgZmFjdG9ycyBiYXNlZCBvbiAxIHNxdWFyZSBtZXRlci5cbiAqXG4gKiBAbWVtYmVyb2YgaGVscGVyc1xuICogQHR5cGUge09iamVjdH1cbiAqL1xuZXhwb3J0cy5hcmVhRmFjdG9ycyA9IHtcbiAgICBhY3JlczogMC4wMDAyNDcxMDUsXG4gICAgY2VudGltZXRlcnM6IDEwMDAwLFxuICAgIGNlbnRpbWV0cmVzOiAxMDAwMCxcbiAgICBmZWV0OiAxMC43NjM5MTA0MTcsXG4gICAgaGVjdGFyZXM6IDAuMDAwMSxcbiAgICBpbmNoZXM6IDE1NTAuMDAzMTAwMDA2LFxuICAgIGtpbG9tZXRlcnM6IDAuMDAwMDAxLFxuICAgIGtpbG9tZXRyZXM6IDAuMDAwMDAxLFxuICAgIG1ldGVyczogMSxcbiAgICBtZXRyZXM6IDEsXG4gICAgbWlsZXM6IDMuODZlLTcsXG4gICAgbWlsbGltZXRlcnM6IDEwMDAwMDAsXG4gICAgbWlsbGltZXRyZXM6IDEwMDAwMDAsXG4gICAgeWFyZHM6IDEuMTk1OTkwMDQ2LFxufTtcbi8qKlxuICogV3JhcHMgYSBHZW9KU09OIHtAbGluayBHZW9tZXRyeX0gaW4gYSBHZW9KU09OIHtAbGluayBGZWF0dXJlfS5cbiAqXG4gKiBAbmFtZSBmZWF0dXJlXG4gKiBAcGFyYW0ge0dlb21ldHJ5fSBnZW9tZXRyeSBpbnB1dCBnZW9tZXRyeVxuICogQHBhcmFtIHtPYmplY3R9IFtwcm9wZXJ0aWVzPXt9XSBhbiBPYmplY3Qgb2Yga2V5LXZhbHVlIHBhaXJzIHRvIGFkZCBhcyBwcm9wZXJ0aWVzXG4gKiBAcGFyYW0ge09iamVjdH0gW29wdGlvbnM9e31dIE9wdGlvbmFsIFBhcmFtZXRlcnNcbiAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyPn0gW29wdGlvbnMuYmJveF0gQm91bmRpbmcgQm94IEFycmF5IFt3ZXN0LCBzb3V0aCwgZWFzdCwgbm9ydGhdIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHBhcmFtIHtzdHJpbmd8bnVtYmVyfSBbb3B0aW9ucy5pZF0gSWRlbnRpZmllciBhc3NvY2lhdGVkIHdpdGggdGhlIEZlYXR1cmVcbiAqIEByZXR1cm5zIHtGZWF0dXJlfSBhIEdlb0pTT04gRmVhdHVyZVxuICogQGV4YW1wbGVcbiAqIHZhciBnZW9tZXRyeSA9IHtcbiAqICAgXCJ0eXBlXCI6IFwiUG9pbnRcIixcbiAqICAgXCJjb29yZGluYXRlc1wiOiBbMTEwLCA1MF1cbiAqIH07XG4gKlxuICogdmFyIGZlYXR1cmUgPSB0dXJmLmZlYXR1cmUoZ2VvbWV0cnkpO1xuICpcbiAqIC8vPWZlYXR1cmVcbiAqL1xuZnVuY3Rpb24gZmVhdHVyZShnZW9tLCBwcm9wZXJ0aWVzLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICB2YXIgZmVhdCA9IHsgdHlwZTogXCJGZWF0dXJlXCIgfTtcbiAgICBpZiAob3B0aW9ucy5pZCA9PT0gMCB8fCBvcHRpb25zLmlkKSB7XG4gICAgICAgIGZlYXQuaWQgPSBvcHRpb25zLmlkO1xuICAgIH1cbiAgICBpZiAob3B0aW9ucy5iYm94KSB7XG4gICAgICAgIGZlYXQuYmJveCA9IG9wdGlvbnMuYmJveDtcbiAgICB9XG4gICAgZmVhdC5wcm9wZXJ0aWVzID0gcHJvcGVydGllcyB8fCB7fTtcbiAgICBmZWF0Lmdlb21ldHJ5ID0gZ2VvbTtcbiAgICByZXR1cm4gZmVhdDtcbn1cbmV4cG9ydHMuZmVhdHVyZSA9IGZlYXR1cmU7XG4vKipcbiAqIENyZWF0ZXMgYSBHZW9KU09OIHtAbGluayBHZW9tZXRyeX0gZnJvbSBhIEdlb21ldHJ5IHN0cmluZyB0eXBlICYgY29vcmRpbmF0ZXMuXG4gKiBGb3IgR2VvbWV0cnlDb2xsZWN0aW9uIHR5cGUgdXNlIGBoZWxwZXJzLmdlb21ldHJ5Q29sbGVjdGlvbmBcbiAqXG4gKiBAbmFtZSBnZW9tZXRyeVxuICogQHBhcmFtIHtzdHJpbmd9IHR5cGUgR2VvbWV0cnkgVHlwZVxuICogQHBhcmFtIHtBcnJheTxhbnk+fSBjb29yZGluYXRlcyBDb29yZGluYXRlc1xuICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zPXt9XSBPcHRpb25hbCBQYXJhbWV0ZXJzXG4gKiBAcmV0dXJucyB7R2VvbWV0cnl9IGEgR2VvSlNPTiBHZW9tZXRyeVxuICogQGV4YW1wbGVcbiAqIHZhciB0eXBlID0gXCJQb2ludFwiO1xuICogdmFyIGNvb3JkaW5hdGVzID0gWzExMCwgNTBdO1xuICogdmFyIGdlb21ldHJ5ID0gdHVyZi5nZW9tZXRyeSh0eXBlLCBjb29yZGluYXRlcyk7XG4gKiAvLyA9PiBnZW9tZXRyeVxuICovXG5mdW5jdGlvbiBnZW9tZXRyeSh0eXBlLCBjb29yZGluYXRlcywgX29wdGlvbnMpIHtcbiAgICBpZiAoX29wdGlvbnMgPT09IHZvaWQgMCkgeyBfb3B0aW9ucyA9IHt9OyB9XG4gICAgc3dpdGNoICh0eXBlKSB7XG4gICAgICAgIGNhc2UgXCJQb2ludFwiOlxuICAgICAgICAgICAgcmV0dXJuIHBvaW50KGNvb3JkaW5hdGVzKS5nZW9tZXRyeTtcbiAgICAgICAgY2FzZSBcIkxpbmVTdHJpbmdcIjpcbiAgICAgICAgICAgIHJldHVybiBsaW5lU3RyaW5nKGNvb3JkaW5hdGVzKS5nZW9tZXRyeTtcbiAgICAgICAgY2FzZSBcIlBvbHlnb25cIjpcbiAgICAgICAgICAgIHJldHVybiBwb2x5Z29uKGNvb3JkaW5hdGVzKS5nZW9tZXRyeTtcbiAgICAgICAgY2FzZSBcIk11bHRpUG9pbnRcIjpcbiAgICAgICAgICAgIHJldHVybiBtdWx0aVBvaW50KGNvb3JkaW5hdGVzKS5nZW9tZXRyeTtcbiAgICAgICAgY2FzZSBcIk11bHRpTGluZVN0cmluZ1wiOlxuICAgICAgICAgICAgcmV0dXJuIG11bHRpTGluZVN0cmluZyhjb29yZGluYXRlcykuZ2VvbWV0cnk7XG4gICAgICAgIGNhc2UgXCJNdWx0aVBvbHlnb25cIjpcbiAgICAgICAgICAgIHJldHVybiBtdWx0aVBvbHlnb24oY29vcmRpbmF0ZXMpLmdlb21ldHJ5O1xuICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKHR5cGUgKyBcIiBpcyBpbnZhbGlkXCIpO1xuICAgIH1cbn1cbmV4cG9ydHMuZ2VvbWV0cnkgPSBnZW9tZXRyeTtcbi8qKlxuICogQ3JlYXRlcyBhIHtAbGluayBQb2ludH0ge0BsaW5rIEZlYXR1cmV9IGZyb20gYSBQb3NpdGlvbi5cbiAqXG4gKiBAbmFtZSBwb2ludFxuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBjb29yZGluYXRlcyBsb25naXR1ZGUsIGxhdGl0dWRlIHBvc2l0aW9uIChlYWNoIGluIGRlY2ltYWwgZGVncmVlcylcbiAqIEBwYXJhbSB7T2JqZWN0fSBbcHJvcGVydGllcz17fV0gYW4gT2JqZWN0IG9mIGtleS12YWx1ZSBwYWlycyB0byBhZGQgYXMgcHJvcGVydGllc1xuICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zPXt9XSBPcHRpb25hbCBQYXJhbWV0ZXJzXG4gKiBAcGFyYW0ge0FycmF5PG51bWJlcj59IFtvcHRpb25zLmJib3hdIEJvdW5kaW5nIEJveCBBcnJheSBbd2VzdCwgc291dGgsIGVhc3QsIG5vcnRoXSBhc3NvY2lhdGVkIHdpdGggdGhlIEZlYXR1cmVcbiAqIEBwYXJhbSB7c3RyaW5nfG51bWJlcn0gW29wdGlvbnMuaWRdIElkZW50aWZpZXIgYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcmV0dXJucyB7RmVhdHVyZTxQb2ludD59IGEgUG9pbnQgZmVhdHVyZVxuICogQGV4YW1wbGVcbiAqIHZhciBwb2ludCA9IHR1cmYucG9pbnQoWy03NS4zNDMsIDM5Ljk4NF0pO1xuICpcbiAqIC8vPXBvaW50XG4gKi9cbmZ1bmN0aW9uIHBvaW50KGNvb3JkaW5hdGVzLCBwcm9wZXJ0aWVzLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICBpZiAoIWNvb3JkaW5hdGVzKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkaW5hdGVzIGlzIHJlcXVpcmVkXCIpO1xuICAgIH1cbiAgICBpZiAoIUFycmF5LmlzQXJyYXkoY29vcmRpbmF0ZXMpKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkaW5hdGVzIG11c3QgYmUgYW4gQXJyYXlcIik7XG4gICAgfVxuICAgIGlmIChjb29yZGluYXRlcy5sZW5ndGggPCAyKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkaW5hdGVzIG11c3QgYmUgYXQgbGVhc3QgMiBudW1iZXJzIGxvbmdcIik7XG4gICAgfVxuICAgIGlmICghaXNOdW1iZXIoY29vcmRpbmF0ZXNbMF0pIHx8ICFpc051bWJlcihjb29yZGluYXRlc1sxXSkpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiY29vcmRpbmF0ZXMgbXVzdCBjb250YWluIG51bWJlcnNcIik7XG4gICAgfVxuICAgIHZhciBnZW9tID0ge1xuICAgICAgICB0eXBlOiBcIlBvaW50XCIsXG4gICAgICAgIGNvb3JkaW5hdGVzOiBjb29yZGluYXRlcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5wb2ludCA9IHBvaW50O1xuLyoqXG4gKiBDcmVhdGVzIGEge0BsaW5rIFBvaW50fSB7QGxpbmsgRmVhdHVyZUNvbGxlY3Rpb259IGZyb20gYW4gQXJyYXkgb2YgUG9pbnQgY29vcmRpbmF0ZXMuXG4gKlxuICogQG5hbWUgcG9pbnRzXG4gKiBAcGFyYW0ge0FycmF5PEFycmF5PG51bWJlcj4+fSBjb29yZGluYXRlcyBhbiBhcnJheSBvZiBQb2ludHNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbcHJvcGVydGllcz17fV0gVHJhbnNsYXRlIHRoZXNlIHByb3BlcnRpZXMgdG8gZWFjaCBGZWF0dXJlXG4gKiBAcGFyYW0ge09iamVjdH0gW29wdGlvbnM9e31dIE9wdGlvbmFsIFBhcmFtZXRlcnNcbiAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyPn0gW29wdGlvbnMuYmJveF0gQm91bmRpbmcgQm94IEFycmF5IFt3ZXN0LCBzb3V0aCwgZWFzdCwgbm9ydGhdXG4gKiBhc3NvY2lhdGVkIHdpdGggdGhlIEZlYXR1cmVDb2xsZWN0aW9uXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZUNvbGxlY3Rpb25cbiAqIEByZXR1cm5zIHtGZWF0dXJlQ29sbGVjdGlvbjxQb2ludD59IFBvaW50IEZlYXR1cmVcbiAqIEBleGFtcGxlXG4gKiB2YXIgcG9pbnRzID0gdHVyZi5wb2ludHMoW1xuICogICBbLTc1LCAzOV0sXG4gKiAgIFstODAsIDQ1XSxcbiAqICAgWy03OCwgNTBdXG4gKiBdKTtcbiAqXG4gKiAvLz1wb2ludHNcbiAqL1xuZnVuY3Rpb24gcG9pbnRzKGNvb3JkaW5hdGVzLCBwcm9wZXJ0aWVzLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICByZXR1cm4gZmVhdHVyZUNvbGxlY3Rpb24oY29vcmRpbmF0ZXMubWFwKGZ1bmN0aW9uIChjb29yZHMpIHtcbiAgICAgICAgcmV0dXJuIHBvaW50KGNvb3JkcywgcHJvcGVydGllcyk7XG4gICAgfSksIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5wb2ludHMgPSBwb2ludHM7XG4vKipcbiAqIENyZWF0ZXMgYSB7QGxpbmsgUG9seWdvbn0ge0BsaW5rIEZlYXR1cmV9IGZyb20gYW4gQXJyYXkgb2YgTGluZWFyUmluZ3MuXG4gKlxuICogQG5hbWUgcG9seWdvblxuICogQHBhcmFtIHtBcnJheTxBcnJheTxBcnJheTxudW1iZXI+Pj59IGNvb3JkaW5hdGVzIGFuIGFycmF5IG9mIExpbmVhclJpbmdzXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHJldHVybnMge0ZlYXR1cmU8UG9seWdvbj59IFBvbHlnb24gRmVhdHVyZVxuICogQGV4YW1wbGVcbiAqIHZhciBwb2x5Z29uID0gdHVyZi5wb2x5Z29uKFtbWy01LCA1Ml0sIFstNCwgNTZdLCBbLTIsIDUxXSwgWy03LCA1NF0sIFstNSwgNTJdXV0sIHsgbmFtZTogJ3BvbHkxJyB9KTtcbiAqXG4gKiAvLz1wb2x5Z29uXG4gKi9cbmZ1bmN0aW9uIHBvbHlnb24oY29vcmRpbmF0ZXMsIHByb3BlcnRpZXMsIG9wdGlvbnMpIHtcbiAgICBpZiAob3B0aW9ucyA9PT0gdm9pZCAwKSB7IG9wdGlvbnMgPSB7fTsgfVxuICAgIGZvciAodmFyIF9pID0gMCwgY29vcmRpbmF0ZXNfMSA9IGNvb3JkaW5hdGVzOyBfaSA8IGNvb3JkaW5hdGVzXzEubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgIHZhciByaW5nID0gY29vcmRpbmF0ZXNfMVtfaV07XG4gICAgICAgIGlmIChyaW5nLmxlbmd0aCA8IDQpIHtcbiAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcihcIkVhY2ggTGluZWFyUmluZyBvZiBhIFBvbHlnb24gbXVzdCBoYXZlIDQgb3IgbW9yZSBQb3NpdGlvbnMuXCIpO1xuICAgICAgICB9XG4gICAgICAgIGZvciAodmFyIGogPSAwOyBqIDwgcmluZ1tyaW5nLmxlbmd0aCAtIDFdLmxlbmd0aDsgaisrKSB7XG4gICAgICAgICAgICAvLyBDaGVjayBpZiBmaXJzdCBwb2ludCBvZiBQb2x5Z29uIGNvbnRhaW5zIHR3byBudW1iZXJzXG4gICAgICAgICAgICBpZiAocmluZ1tyaW5nLmxlbmd0aCAtIDFdW2pdICE9PSByaW5nWzBdW2pdKSB7XG4gICAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiRmlyc3QgYW5kIGxhc3QgUG9zaXRpb24gYXJlIG5vdCBlcXVpdmFsZW50LlwiKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cbiAgICB2YXIgZ2VvbSA9IHtcbiAgICAgICAgdHlwZTogXCJQb2x5Z29uXCIsXG4gICAgICAgIGNvb3JkaW5hdGVzOiBjb29yZGluYXRlcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5wb2x5Z29uID0gcG9seWdvbjtcbi8qKlxuICogQ3JlYXRlcyBhIHtAbGluayBQb2x5Z29ufSB7QGxpbmsgRmVhdHVyZUNvbGxlY3Rpb259IGZyb20gYW4gQXJyYXkgb2YgUG9seWdvbiBjb29yZGluYXRlcy5cbiAqXG4gKiBAbmFtZSBwb2x5Z29uc1xuICogQHBhcmFtIHtBcnJheTxBcnJheTxBcnJheTxBcnJheTxudW1iZXI+Pj4+fSBjb29yZGluYXRlcyBhbiBhcnJheSBvZiBQb2x5Z29uIGNvb3JkaW5hdGVzXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZUNvbGxlY3Rpb25cbiAqIEByZXR1cm5zIHtGZWF0dXJlQ29sbGVjdGlvbjxQb2x5Z29uPn0gUG9seWdvbiBGZWF0dXJlQ29sbGVjdGlvblxuICogQGV4YW1wbGVcbiAqIHZhciBwb2x5Z29ucyA9IHR1cmYucG9seWdvbnMoW1xuICogICBbW1stNSwgNTJdLCBbLTQsIDU2XSwgWy0yLCA1MV0sIFstNywgNTRdLCBbLTUsIDUyXV1dLFxuICogICBbW1stMTUsIDQyXSwgWy0xNCwgNDZdLCBbLTEyLCA0MV0sIFstMTcsIDQ0XSwgWy0xNSwgNDJdXV0sXG4gKiBdKTtcbiAqXG4gKiAvLz1wb2x5Z29uc1xuICovXG5mdW5jdGlvbiBwb2x5Z29ucyhjb29yZGluYXRlcywgcHJvcGVydGllcywgb3B0aW9ucykge1xuICAgIGlmIChvcHRpb25zID09PSB2b2lkIDApIHsgb3B0aW9ucyA9IHt9OyB9XG4gICAgcmV0dXJuIGZlYXR1cmVDb2xsZWN0aW9uKGNvb3JkaW5hdGVzLm1hcChmdW5jdGlvbiAoY29vcmRzKSB7XG4gICAgICAgIHJldHVybiBwb2x5Z29uKGNvb3JkcywgcHJvcGVydGllcyk7XG4gICAgfSksIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5wb2x5Z29ucyA9IHBvbHlnb25zO1xuLyoqXG4gKiBDcmVhdGVzIGEge0BsaW5rIExpbmVTdHJpbmd9IHtAbGluayBGZWF0dXJlfSBmcm9tIGFuIEFycmF5IG9mIFBvc2l0aW9ucy5cbiAqXG4gKiBAbmFtZSBsaW5lU3RyaW5nXG4gKiBAcGFyYW0ge0FycmF5PEFycmF5PG51bWJlcj4+fSBjb29yZGluYXRlcyBhbiBhcnJheSBvZiBQb3NpdGlvbnNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbcHJvcGVydGllcz17fV0gYW4gT2JqZWN0IG9mIGtleS12YWx1ZSBwYWlycyB0byBhZGQgYXMgcHJvcGVydGllc1xuICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zPXt9XSBPcHRpb25hbCBQYXJhbWV0ZXJzXG4gKiBAcGFyYW0ge0FycmF5PG51bWJlcj59IFtvcHRpb25zLmJib3hdIEJvdW5kaW5nIEJveCBBcnJheSBbd2VzdCwgc291dGgsIGVhc3QsIG5vcnRoXSBhc3NvY2lhdGVkIHdpdGggdGhlIEZlYXR1cmVcbiAqIEBwYXJhbSB7c3RyaW5nfG51bWJlcn0gW29wdGlvbnMuaWRdIElkZW50aWZpZXIgYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcmV0dXJucyB7RmVhdHVyZTxMaW5lU3RyaW5nPn0gTGluZVN0cmluZyBGZWF0dXJlXG4gKiBAZXhhbXBsZVxuICogdmFyIGxpbmVzdHJpbmcxID0gdHVyZi5saW5lU3RyaW5nKFtbLTI0LCA2M10sIFstMjMsIDYwXSwgWy0yNSwgNjVdLCBbLTIwLCA2OV1dLCB7bmFtZTogJ2xpbmUgMSd9KTtcbiAqIHZhciBsaW5lc3RyaW5nMiA9IHR1cmYubGluZVN0cmluZyhbWy0xNCwgNDNdLCBbLTEzLCA0MF0sIFstMTUsIDQ1XSwgWy0xMCwgNDldXSwge25hbWU6ICdsaW5lIDInfSk7XG4gKlxuICogLy89bGluZXN0cmluZzFcbiAqIC8vPWxpbmVzdHJpbmcyXG4gKi9cbmZ1bmN0aW9uIGxpbmVTdHJpbmcoY29vcmRpbmF0ZXMsIHByb3BlcnRpZXMsIG9wdGlvbnMpIHtcbiAgICBpZiAob3B0aW9ucyA9PT0gdm9pZCAwKSB7IG9wdGlvbnMgPSB7fTsgfVxuICAgIGlmIChjb29yZGluYXRlcy5sZW5ndGggPCAyKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkaW5hdGVzIG11c3QgYmUgYW4gYXJyYXkgb2YgdHdvIG9yIG1vcmUgcG9zaXRpb25zXCIpO1xuICAgIH1cbiAgICB2YXIgZ2VvbSA9IHtcbiAgICAgICAgdHlwZTogXCJMaW5lU3RyaW5nXCIsXG4gICAgICAgIGNvb3JkaW5hdGVzOiBjb29yZGluYXRlcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5saW5lU3RyaW5nID0gbGluZVN0cmluZztcbi8qKlxuICogQ3JlYXRlcyBhIHtAbGluayBMaW5lU3RyaW5nfSB7QGxpbmsgRmVhdHVyZUNvbGxlY3Rpb259IGZyb20gYW4gQXJyYXkgb2YgTGluZVN0cmluZyBjb29yZGluYXRlcy5cbiAqXG4gKiBAbmFtZSBsaW5lU3RyaW5nc1xuICogQHBhcmFtIHtBcnJheTxBcnJheTxBcnJheTxudW1iZXI+Pj59IGNvb3JkaW5hdGVzIGFuIGFycmF5IG9mIExpbmVhclJpbmdzXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF1cbiAqIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZUNvbGxlY3Rpb25cbiAqIEBwYXJhbSB7c3RyaW5nfG51bWJlcn0gW29wdGlvbnMuaWRdIElkZW50aWZpZXIgYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlQ29sbGVjdGlvblxuICogQHJldHVybnMge0ZlYXR1cmVDb2xsZWN0aW9uPExpbmVTdHJpbmc+fSBMaW5lU3RyaW5nIEZlYXR1cmVDb2xsZWN0aW9uXG4gKiBAZXhhbXBsZVxuICogdmFyIGxpbmVzdHJpbmdzID0gdHVyZi5saW5lU3RyaW5ncyhbXG4gKiAgIFtbLTI0LCA2M10sIFstMjMsIDYwXSwgWy0yNSwgNjVdLCBbLTIwLCA2OV1dLFxuICogICBbWy0xNCwgNDNdLCBbLTEzLCA0MF0sIFstMTUsIDQ1XSwgWy0xMCwgNDldXVxuICogXSk7XG4gKlxuICogLy89bGluZXN0cmluZ3NcbiAqL1xuZnVuY3Rpb24gbGluZVN0cmluZ3MoY29vcmRpbmF0ZXMsIHByb3BlcnRpZXMsIG9wdGlvbnMpIHtcbiAgICBpZiAob3B0aW9ucyA9PT0gdm9pZCAwKSB7IG9wdGlvbnMgPSB7fTsgfVxuICAgIHJldHVybiBmZWF0dXJlQ29sbGVjdGlvbihjb29yZGluYXRlcy5tYXAoZnVuY3Rpb24gKGNvb3Jkcykge1xuICAgICAgICByZXR1cm4gbGluZVN0cmluZyhjb29yZHMsIHByb3BlcnRpZXMpO1xuICAgIH0pLCBvcHRpb25zKTtcbn1cbmV4cG9ydHMubGluZVN0cmluZ3MgPSBsaW5lU3RyaW5ncztcbi8qKlxuICogVGFrZXMgb25lIG9yIG1vcmUge0BsaW5rIEZlYXR1cmV8RmVhdHVyZXN9IGFuZCBjcmVhdGVzIGEge0BsaW5rIEZlYXR1cmVDb2xsZWN0aW9ufS5cbiAqXG4gKiBAbmFtZSBmZWF0dXJlQ29sbGVjdGlvblxuICogQHBhcmFtIHtGZWF0dXJlW119IGZlYXR1cmVzIGlucHV0IGZlYXR1cmVzXG4gKiBAcGFyYW0ge09iamVjdH0gW29wdGlvbnM9e31dIE9wdGlvbmFsIFBhcmFtZXRlcnNcbiAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyPn0gW29wdGlvbnMuYmJveF0gQm91bmRpbmcgQm94IEFycmF5IFt3ZXN0LCBzb3V0aCwgZWFzdCwgbm9ydGhdIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHBhcmFtIHtzdHJpbmd8bnVtYmVyfSBbb3B0aW9ucy5pZF0gSWRlbnRpZmllciBhc3NvY2lhdGVkIHdpdGggdGhlIEZlYXR1cmVcbiAqIEByZXR1cm5zIHtGZWF0dXJlQ29sbGVjdGlvbn0gRmVhdHVyZUNvbGxlY3Rpb24gb2YgRmVhdHVyZXNcbiAqIEBleGFtcGxlXG4gKiB2YXIgbG9jYXRpb25BID0gdHVyZi5wb2ludChbLTc1LjM0MywgMzkuOTg0XSwge25hbWU6ICdMb2NhdGlvbiBBJ30pO1xuICogdmFyIGxvY2F0aW9uQiA9IHR1cmYucG9pbnQoWy03NS44MzMsIDM5LjI4NF0sIHtuYW1lOiAnTG9jYXRpb24gQid9KTtcbiAqIHZhciBsb2NhdGlvbkMgPSB0dXJmLnBvaW50KFstNzUuNTM0LCAzOS4xMjNdLCB7bmFtZTogJ0xvY2F0aW9uIEMnfSk7XG4gKlxuICogdmFyIGNvbGxlY3Rpb24gPSB0dXJmLmZlYXR1cmVDb2xsZWN0aW9uKFtcbiAqICAgbG9jYXRpb25BLFxuICogICBsb2NhdGlvbkIsXG4gKiAgIGxvY2F0aW9uQ1xuICogXSk7XG4gKlxuICogLy89Y29sbGVjdGlvblxuICovXG5mdW5jdGlvbiBmZWF0dXJlQ29sbGVjdGlvbihmZWF0dXJlcywgb3B0aW9ucykge1xuICAgIGlmIChvcHRpb25zID09PSB2b2lkIDApIHsgb3B0aW9ucyA9IHt9OyB9XG4gICAgdmFyIGZjID0geyB0eXBlOiBcIkZlYXR1cmVDb2xsZWN0aW9uXCIgfTtcbiAgICBpZiAob3B0aW9ucy5pZCkge1xuICAgICAgICBmYy5pZCA9IG9wdGlvbnMuaWQ7XG4gICAgfVxuICAgIGlmIChvcHRpb25zLmJib3gpIHtcbiAgICAgICAgZmMuYmJveCA9IG9wdGlvbnMuYmJveDtcbiAgICB9XG4gICAgZmMuZmVhdHVyZXMgPSBmZWF0dXJlcztcbiAgICByZXR1cm4gZmM7XG59XG5leHBvcnRzLmZlYXR1cmVDb2xsZWN0aW9uID0gZmVhdHVyZUNvbGxlY3Rpb247XG4vKipcbiAqIENyZWF0ZXMgYSB7QGxpbmsgRmVhdHVyZTxNdWx0aUxpbmVTdHJpbmc+fSBiYXNlZCBvbiBhXG4gKiBjb29yZGluYXRlIGFycmF5LiBQcm9wZXJ0aWVzIGNhbiBiZSBhZGRlZCBvcHRpb25hbGx5LlxuICpcbiAqIEBuYW1lIG11bHRpTGluZVN0cmluZ1xuICogQHBhcmFtIHtBcnJheTxBcnJheTxBcnJheTxudW1iZXI+Pj59IGNvb3JkaW5hdGVzIGFuIGFycmF5IG9mIExpbmVTdHJpbmdzXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHJldHVybnMge0ZlYXR1cmU8TXVsdGlMaW5lU3RyaW5nPn0gYSBNdWx0aUxpbmVTdHJpbmcgZmVhdHVyZVxuICogQHRocm93cyB7RXJyb3J9IGlmIG5vIGNvb3JkaW5hdGVzIGFyZSBwYXNzZWRcbiAqIEBleGFtcGxlXG4gKiB2YXIgbXVsdGlMaW5lID0gdHVyZi5tdWx0aUxpbmVTdHJpbmcoW1tbMCwwXSxbMTAsMTBdXV0pO1xuICpcbiAqIC8vPW11bHRpTGluZVxuICovXG5mdW5jdGlvbiBtdWx0aUxpbmVTdHJpbmcoY29vcmRpbmF0ZXMsIHByb3BlcnRpZXMsIG9wdGlvbnMpIHtcbiAgICBpZiAob3B0aW9ucyA9PT0gdm9pZCAwKSB7IG9wdGlvbnMgPSB7fTsgfVxuICAgIHZhciBnZW9tID0ge1xuICAgICAgICB0eXBlOiBcIk11bHRpTGluZVN0cmluZ1wiLFxuICAgICAgICBjb29yZGluYXRlczogY29vcmRpbmF0ZXMsXG4gICAgfTtcbiAgICByZXR1cm4gZmVhdHVyZShnZW9tLCBwcm9wZXJ0aWVzLCBvcHRpb25zKTtcbn1cbmV4cG9ydHMubXVsdGlMaW5lU3RyaW5nID0gbXVsdGlMaW5lU3RyaW5nO1xuLyoqXG4gKiBDcmVhdGVzIGEge0BsaW5rIEZlYXR1cmU8TXVsdGlQb2ludD59IGJhc2VkIG9uIGFcbiAqIGNvb3JkaW5hdGUgYXJyYXkuIFByb3BlcnRpZXMgY2FuIGJlIGFkZGVkIG9wdGlvbmFsbHkuXG4gKlxuICogQG5hbWUgbXVsdGlQb2ludFxuICogQHBhcmFtIHtBcnJheTxBcnJheTxudW1iZXI+Pn0gY29vcmRpbmF0ZXMgYW4gYXJyYXkgb2YgUG9zaXRpb25zXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHJldHVybnMge0ZlYXR1cmU8TXVsdGlQb2ludD59IGEgTXVsdGlQb2ludCBmZWF0dXJlXG4gKiBAdGhyb3dzIHtFcnJvcn0gaWYgbm8gY29vcmRpbmF0ZXMgYXJlIHBhc3NlZFxuICogQGV4YW1wbGVcbiAqIHZhciBtdWx0aVB0ID0gdHVyZi5tdWx0aVBvaW50KFtbMCwwXSxbMTAsMTBdXSk7XG4gKlxuICogLy89bXVsdGlQdFxuICovXG5mdW5jdGlvbiBtdWx0aVBvaW50KGNvb3JkaW5hdGVzLCBwcm9wZXJ0aWVzLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICB2YXIgZ2VvbSA9IHtcbiAgICAgICAgdHlwZTogXCJNdWx0aVBvaW50XCIsXG4gICAgICAgIGNvb3JkaW5hdGVzOiBjb29yZGluYXRlcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5tdWx0aVBvaW50ID0gbXVsdGlQb2ludDtcbi8qKlxuICogQ3JlYXRlcyBhIHtAbGluayBGZWF0dXJlPE11bHRpUG9seWdvbj59IGJhc2VkIG9uIGFcbiAqIGNvb3JkaW5hdGUgYXJyYXkuIFByb3BlcnRpZXMgY2FuIGJlIGFkZGVkIG9wdGlvbmFsbHkuXG4gKlxuICogQG5hbWUgbXVsdGlQb2x5Z29uXG4gKiBAcGFyYW0ge0FycmF5PEFycmF5PEFycmF5PEFycmF5PG51bWJlcj4+Pj59IGNvb3JkaW5hdGVzIGFuIGFycmF5IG9mIFBvbHlnb25zXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHJldHVybnMge0ZlYXR1cmU8TXVsdGlQb2x5Z29uPn0gYSBtdWx0aXBvbHlnb24gZmVhdHVyZVxuICogQHRocm93cyB7RXJyb3J9IGlmIG5vIGNvb3JkaW5hdGVzIGFyZSBwYXNzZWRcbiAqIEBleGFtcGxlXG4gKiB2YXIgbXVsdGlQb2x5ID0gdHVyZi5tdWx0aVBvbHlnb24oW1tbWzAsMF0sWzAsMTBdLFsxMCwxMF0sWzEwLDBdLFswLDBdXV1dKTtcbiAqXG4gKiAvLz1tdWx0aVBvbHlcbiAqXG4gKi9cbmZ1bmN0aW9uIG11bHRpUG9seWdvbihjb29yZGluYXRlcywgcHJvcGVydGllcywgb3B0aW9ucykge1xuICAgIGlmIChvcHRpb25zID09PSB2b2lkIDApIHsgb3B0aW9ucyA9IHt9OyB9XG4gICAgdmFyIGdlb20gPSB7XG4gICAgICAgIHR5cGU6IFwiTXVsdGlQb2x5Z29uXCIsXG4gICAgICAgIGNvb3JkaW5hdGVzOiBjb29yZGluYXRlcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5tdWx0aVBvbHlnb24gPSBtdWx0aVBvbHlnb247XG4vKipcbiAqIENyZWF0ZXMgYSB7QGxpbmsgRmVhdHVyZTxHZW9tZXRyeUNvbGxlY3Rpb24+fSBiYXNlZCBvbiBhXG4gKiBjb29yZGluYXRlIGFycmF5LiBQcm9wZXJ0aWVzIGNhbiBiZSBhZGRlZCBvcHRpb25hbGx5LlxuICpcbiAqIEBuYW1lIGdlb21ldHJ5Q29sbGVjdGlvblxuICogQHBhcmFtIHtBcnJheTxHZW9tZXRyeT59IGdlb21ldHJpZXMgYW4gYXJyYXkgb2YgR2VvSlNPTiBHZW9tZXRyaWVzXG4gKiBAcGFyYW0ge09iamVjdH0gW3Byb3BlcnRpZXM9e31dIGFuIE9iamVjdCBvZiBrZXktdmFsdWUgcGFpcnMgdG8gYWRkIGFzIHByb3BlcnRpZXNcbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9ucz17fV0gT3B0aW9uYWwgUGFyYW1ldGVyc1xuICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSBbb3B0aW9ucy5iYm94XSBCb3VuZGluZyBCb3ggQXJyYXkgW3dlc3QsIHNvdXRoLCBlYXN0LCBub3J0aF0gYXNzb2NpYXRlZCB3aXRoIHRoZSBGZWF0dXJlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IFtvcHRpb25zLmlkXSBJZGVudGlmaWVyIGFzc29jaWF0ZWQgd2l0aCB0aGUgRmVhdHVyZVxuICogQHJldHVybnMge0ZlYXR1cmU8R2VvbWV0cnlDb2xsZWN0aW9uPn0gYSBHZW9KU09OIEdlb21ldHJ5Q29sbGVjdGlvbiBGZWF0dXJlXG4gKiBAZXhhbXBsZVxuICogdmFyIHB0ID0gdHVyZi5nZW9tZXRyeShcIlBvaW50XCIsIFsxMDAsIDBdKTtcbiAqIHZhciBsaW5lID0gdHVyZi5nZW9tZXRyeShcIkxpbmVTdHJpbmdcIiwgW1sxMDEsIDBdLCBbMTAyLCAxXV0pO1xuICogdmFyIGNvbGxlY3Rpb24gPSB0dXJmLmdlb21ldHJ5Q29sbGVjdGlvbihbcHQsIGxpbmVdKTtcbiAqXG4gKiAvLyA9PiBjb2xsZWN0aW9uXG4gKi9cbmZ1bmN0aW9uIGdlb21ldHJ5Q29sbGVjdGlvbihnZW9tZXRyaWVzLCBwcm9wZXJ0aWVzLCBvcHRpb25zKSB7XG4gICAgaWYgKG9wdGlvbnMgPT09IHZvaWQgMCkgeyBvcHRpb25zID0ge307IH1cbiAgICB2YXIgZ2VvbSA9IHtcbiAgICAgICAgdHlwZTogXCJHZW9tZXRyeUNvbGxlY3Rpb25cIixcbiAgICAgICAgZ2VvbWV0cmllczogZ2VvbWV0cmllcyxcbiAgICB9O1xuICAgIHJldHVybiBmZWF0dXJlKGdlb20sIHByb3BlcnRpZXMsIG9wdGlvbnMpO1xufVxuZXhwb3J0cy5nZW9tZXRyeUNvbGxlY3Rpb24gPSBnZW9tZXRyeUNvbGxlY3Rpb247XG4vKipcbiAqIFJvdW5kIG51bWJlciB0byBwcmVjaXNpb25cbiAqXG4gKiBAcGFyYW0ge251bWJlcn0gbnVtIE51bWJlclxuICogQHBhcmFtIHtudW1iZXJ9IFtwcmVjaXNpb249MF0gUHJlY2lzaW9uXG4gKiBAcmV0dXJucyB7bnVtYmVyfSByb3VuZGVkIG51bWJlclxuICogQGV4YW1wbGVcbiAqIHR1cmYucm91bmQoMTIwLjQzMjEpXG4gKiAvLz0xMjBcbiAqXG4gKiB0dXJmLnJvdW5kKDEyMC40MzIxLCAyKVxuICogLy89MTIwLjQzXG4gKi9cbmZ1bmN0aW9uIHJvdW5kKG51bSwgcHJlY2lzaW9uKSB7XG4gICAgaWYgKHByZWNpc2lvbiA9PT0gdm9pZCAwKSB7IHByZWNpc2lvbiA9IDA7IH1cbiAgICBpZiAocHJlY2lzaW9uICYmICEocHJlY2lzaW9uID49IDApKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcInByZWNpc2lvbiBtdXN0IGJlIGEgcG9zaXRpdmUgbnVtYmVyXCIpO1xuICAgIH1cbiAgICB2YXIgbXVsdGlwbGllciA9IE1hdGgucG93KDEwLCBwcmVjaXNpb24gfHwgMCk7XG4gICAgcmV0dXJuIE1hdGgucm91bmQobnVtICogbXVsdGlwbGllcikgLyBtdWx0aXBsaWVyO1xufVxuZXhwb3J0cy5yb3VuZCA9IHJvdW5kO1xuLyoqXG4gKiBDb252ZXJ0IGEgZGlzdGFuY2UgbWVhc3VyZW1lbnQgKGFzc3VtaW5nIGEgc3BoZXJpY2FsIEVhcnRoKSBmcm9tIHJhZGlhbnMgdG8gYSBtb3JlIGZyaWVuZGx5IHVuaXQuXG4gKiBWYWxpZCB1bml0czogbWlsZXMsIG5hdXRpY2FsbWlsZXMsIGluY2hlcywgeWFyZHMsIG1ldGVycywgbWV0cmVzLCBraWxvbWV0ZXJzLCBjZW50aW1ldGVycywgZmVldFxuICpcbiAqIEBuYW1lIHJhZGlhbnNUb0xlbmd0aFxuICogQHBhcmFtIHtudW1iZXJ9IHJhZGlhbnMgaW4gcmFkaWFucyBhY3Jvc3MgdGhlIHNwaGVyZVxuICogQHBhcmFtIHtzdHJpbmd9IFt1bml0cz1cImtpbG9tZXRlcnNcIl0gY2FuIGJlIGRlZ3JlZXMsIHJhZGlhbnMsIG1pbGVzLCBpbmNoZXMsIHlhcmRzLCBtZXRyZXMsXG4gKiBtZXRlcnMsIGtpbG9tZXRyZXMsIGtpbG9tZXRlcnMuXG4gKiBAcmV0dXJucyB7bnVtYmVyfSBkaXN0YW5jZVxuICovXG5mdW5jdGlvbiByYWRpYW5zVG9MZW5ndGgocmFkaWFucywgdW5pdHMpIHtcbiAgICBpZiAodW5pdHMgPT09IHZvaWQgMCkgeyB1bml0cyA9IFwia2lsb21ldGVyc1wiOyB9XG4gICAgdmFyIGZhY3RvciA9IGV4cG9ydHMuZmFjdG9yc1t1bml0c107XG4gICAgaWYgKCFmYWN0b3IpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKHVuaXRzICsgXCIgdW5pdHMgaXMgaW52YWxpZFwiKTtcbiAgICB9XG4gICAgcmV0dXJuIHJhZGlhbnMgKiBmYWN0b3I7XG59XG5leHBvcnRzLnJhZGlhbnNUb0xlbmd0aCA9IHJhZGlhbnNUb0xlbmd0aDtcbi8qKlxuICogQ29udmVydCBhIGRpc3RhbmNlIG1lYXN1cmVtZW50IChhc3N1bWluZyBhIHNwaGVyaWNhbCBFYXJ0aCkgZnJvbSBhIHJlYWwtd29ybGQgdW5pdCBpbnRvIHJhZGlhbnNcbiAqIFZhbGlkIHVuaXRzOiBtaWxlcywgbmF1dGljYWxtaWxlcywgaW5jaGVzLCB5YXJkcywgbWV0ZXJzLCBtZXRyZXMsIGtpbG9tZXRlcnMsIGNlbnRpbWV0ZXJzLCBmZWV0XG4gKlxuICogQG5hbWUgbGVuZ3RoVG9SYWRpYW5zXG4gKiBAcGFyYW0ge251bWJlcn0gZGlzdGFuY2UgaW4gcmVhbCB1bml0c1xuICogQHBhcmFtIHtzdHJpbmd9IFt1bml0cz1cImtpbG9tZXRlcnNcIl0gY2FuIGJlIGRlZ3JlZXMsIHJhZGlhbnMsIG1pbGVzLCBpbmNoZXMsIHlhcmRzLCBtZXRyZXMsXG4gKiBtZXRlcnMsIGtpbG9tZXRyZXMsIGtpbG9tZXRlcnMuXG4gKiBAcmV0dXJucyB7bnVtYmVyfSByYWRpYW5zXG4gKi9cbmZ1bmN0aW9uIGxlbmd0aFRvUmFkaWFucyhkaXN0YW5jZSwgdW5pdHMpIHtcbiAgICBpZiAodW5pdHMgPT09IHZvaWQgMCkgeyB1bml0cyA9IFwia2lsb21ldGVyc1wiOyB9XG4gICAgdmFyIGZhY3RvciA9IGV4cG9ydHMuZmFjdG9yc1t1bml0c107XG4gICAgaWYgKCFmYWN0b3IpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKHVuaXRzICsgXCIgdW5pdHMgaXMgaW52YWxpZFwiKTtcbiAgICB9XG4gICAgcmV0dXJuIGRpc3RhbmNlIC8gZmFjdG9yO1xufVxuZXhwb3J0cy5sZW5ndGhUb1JhZGlhbnMgPSBsZW5ndGhUb1JhZGlhbnM7XG4vKipcbiAqIENvbnZlcnQgYSBkaXN0YW5jZSBtZWFzdXJlbWVudCAoYXNzdW1pbmcgYSBzcGhlcmljYWwgRWFydGgpIGZyb20gYSByZWFsLXdvcmxkIHVuaXQgaW50byBkZWdyZWVzXG4gKiBWYWxpZCB1bml0czogbWlsZXMsIG5hdXRpY2FsbWlsZXMsIGluY2hlcywgeWFyZHMsIG1ldGVycywgbWV0cmVzLCBjZW50aW1ldGVycywga2lsb21ldHJlcywgZmVldFxuICpcbiAqIEBuYW1lIGxlbmd0aFRvRGVncmVlc1xuICogQHBhcmFtIHtudW1iZXJ9IGRpc3RhbmNlIGluIHJlYWwgdW5pdHNcbiAqIEBwYXJhbSB7c3RyaW5nfSBbdW5pdHM9XCJraWxvbWV0ZXJzXCJdIGNhbiBiZSBkZWdyZWVzLCByYWRpYW5zLCBtaWxlcywgaW5jaGVzLCB5YXJkcywgbWV0cmVzLFxuICogbWV0ZXJzLCBraWxvbWV0cmVzLCBraWxvbWV0ZXJzLlxuICogQHJldHVybnMge251bWJlcn0gZGVncmVlc1xuICovXG5mdW5jdGlvbiBsZW5ndGhUb0RlZ3JlZXMoZGlzdGFuY2UsIHVuaXRzKSB7XG4gICAgcmV0dXJuIHJhZGlhbnNUb0RlZ3JlZXMobGVuZ3RoVG9SYWRpYW5zKGRpc3RhbmNlLCB1bml0cykpO1xufVxuZXhwb3J0cy5sZW5ndGhUb0RlZ3JlZXMgPSBsZW5ndGhUb0RlZ3JlZXM7XG4vKipcbiAqIENvbnZlcnRzIGFueSBiZWFyaW5nIGFuZ2xlIGZyb20gdGhlIG5vcnRoIGxpbmUgZGlyZWN0aW9uIChwb3NpdGl2ZSBjbG9ja3dpc2UpXG4gKiBhbmQgcmV0dXJucyBhbiBhbmdsZSBiZXR3ZWVuIDAtMzYwIGRlZ3JlZXMgKHBvc2l0aXZlIGNsb2Nrd2lzZSksIDAgYmVpbmcgdGhlIG5vcnRoIGxpbmVcbiAqXG4gKiBAbmFtZSBiZWFyaW5nVG9BemltdXRoXG4gKiBAcGFyYW0ge251bWJlcn0gYmVhcmluZyBhbmdsZSwgYmV0d2VlbiAtMTgwIGFuZCArMTgwIGRlZ3JlZXNcbiAqIEByZXR1cm5zIHtudW1iZXJ9IGFuZ2xlIGJldHdlZW4gMCBhbmQgMzYwIGRlZ3JlZXNcbiAqL1xuZnVuY3Rpb24gYmVhcmluZ1RvQXppbXV0aChiZWFyaW5nKSB7XG4gICAgdmFyIGFuZ2xlID0gYmVhcmluZyAlIDM2MDtcbiAgICBpZiAoYW5nbGUgPCAwKSB7XG4gICAgICAgIGFuZ2xlICs9IDM2MDtcbiAgICB9XG4gICAgcmV0dXJuIGFuZ2xlO1xufVxuZXhwb3J0cy5iZWFyaW5nVG9BemltdXRoID0gYmVhcmluZ1RvQXppbXV0aDtcbi8qKlxuICogQ29udmVydHMgYW4gYW5nbGUgaW4gcmFkaWFucyB0byBkZWdyZWVzXG4gKlxuICogQG5hbWUgcmFkaWFuc1RvRGVncmVlc1xuICogQHBhcmFtIHtudW1iZXJ9IHJhZGlhbnMgYW5nbGUgaW4gcmFkaWFuc1xuICogQHJldHVybnMge251bWJlcn0gZGVncmVlcyBiZXR3ZWVuIDAgYW5kIDM2MCBkZWdyZWVzXG4gKi9cbmZ1bmN0aW9uIHJhZGlhbnNUb0RlZ3JlZXMocmFkaWFucykge1xuICAgIHZhciBkZWdyZWVzID0gcmFkaWFucyAlICgyICogTWF0aC5QSSk7XG4gICAgcmV0dXJuIChkZWdyZWVzICogMTgwKSAvIE1hdGguUEk7XG59XG5leHBvcnRzLnJhZGlhbnNUb0RlZ3JlZXMgPSByYWRpYW5zVG9EZWdyZWVzO1xuLyoqXG4gKiBDb252ZXJ0cyBhbiBhbmdsZSBpbiBkZWdyZWVzIHRvIHJhZGlhbnNcbiAqXG4gKiBAbmFtZSBkZWdyZWVzVG9SYWRpYW5zXG4gKiBAcGFyYW0ge251bWJlcn0gZGVncmVlcyBhbmdsZSBiZXR3ZWVuIDAgYW5kIDM2MCBkZWdyZWVzXG4gKiBAcmV0dXJucyB7bnVtYmVyfSBhbmdsZSBpbiByYWRpYW5zXG4gKi9cbmZ1bmN0aW9uIGRlZ3JlZXNUb1JhZGlhbnMoZGVncmVlcykge1xuICAgIHZhciByYWRpYW5zID0gZGVncmVlcyAlIDM2MDtcbiAgICByZXR1cm4gKHJhZGlhbnMgKiBNYXRoLlBJKSAvIDE4MDtcbn1cbmV4cG9ydHMuZGVncmVlc1RvUmFkaWFucyA9IGRlZ3JlZXNUb1JhZGlhbnM7XG4vKipcbiAqIENvbnZlcnRzIGEgbGVuZ3RoIHRvIHRoZSByZXF1ZXN0ZWQgdW5pdC5cbiAqIFZhbGlkIHVuaXRzOiBtaWxlcywgbmF1dGljYWxtaWxlcywgaW5jaGVzLCB5YXJkcywgbWV0ZXJzLCBtZXRyZXMsIGtpbG9tZXRlcnMsIGNlbnRpbWV0ZXJzLCBmZWV0XG4gKlxuICogQHBhcmFtIHtudW1iZXJ9IGxlbmd0aCB0byBiZSBjb252ZXJ0ZWRcbiAqIEBwYXJhbSB7VW5pdHN9IFtvcmlnaW5hbFVuaXQ9XCJraWxvbWV0ZXJzXCJdIG9mIHRoZSBsZW5ndGhcbiAqIEBwYXJhbSB7VW5pdHN9IFtmaW5hbFVuaXQ9XCJraWxvbWV0ZXJzXCJdIHJldHVybmVkIHVuaXRcbiAqIEByZXR1cm5zIHtudW1iZXJ9IHRoZSBjb252ZXJ0ZWQgbGVuZ3RoXG4gKi9cbmZ1bmN0aW9uIGNvbnZlcnRMZW5ndGgobGVuZ3RoLCBvcmlnaW5hbFVuaXQsIGZpbmFsVW5pdCkge1xuICAgIGlmIChvcmlnaW5hbFVuaXQgPT09IHZvaWQgMCkgeyBvcmlnaW5hbFVuaXQgPSBcImtpbG9tZXRlcnNcIjsgfVxuICAgIGlmIChmaW5hbFVuaXQgPT09IHZvaWQgMCkgeyBmaW5hbFVuaXQgPSBcImtpbG9tZXRlcnNcIjsgfVxuICAgIGlmICghKGxlbmd0aCA+PSAwKSkge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJsZW5ndGggbXVzdCBiZSBhIHBvc2l0aXZlIG51bWJlclwiKTtcbiAgICB9XG4gICAgcmV0dXJuIHJhZGlhbnNUb0xlbmd0aChsZW5ndGhUb1JhZGlhbnMobGVuZ3RoLCBvcmlnaW5hbFVuaXQpLCBmaW5hbFVuaXQpO1xufVxuZXhwb3J0cy5jb252ZXJ0TGVuZ3RoID0gY29udmVydExlbmd0aDtcbi8qKlxuICogQ29udmVydHMgYSBhcmVhIHRvIHRoZSByZXF1ZXN0ZWQgdW5pdC5cbiAqIFZhbGlkIHVuaXRzOiBraWxvbWV0ZXJzLCBraWxvbWV0cmVzLCBtZXRlcnMsIG1ldHJlcywgY2VudGltZXRyZXMsIG1pbGxpbWV0ZXJzLCBhY3JlcywgbWlsZXMsIHlhcmRzLCBmZWV0LCBpbmNoZXMsIGhlY3RhcmVzXG4gKiBAcGFyYW0ge251bWJlcn0gYXJlYSB0byBiZSBjb252ZXJ0ZWRcbiAqIEBwYXJhbSB7VW5pdHN9IFtvcmlnaW5hbFVuaXQ9XCJtZXRlcnNcIl0gb2YgdGhlIGRpc3RhbmNlXG4gKiBAcGFyYW0ge1VuaXRzfSBbZmluYWxVbml0PVwia2lsb21ldGVyc1wiXSByZXR1cm5lZCB1bml0XG4gKiBAcmV0dXJucyB7bnVtYmVyfSB0aGUgY29udmVydGVkIGFyZWFcbiAqL1xuZnVuY3Rpb24gY29udmVydEFyZWEoYXJlYSwgb3JpZ2luYWxVbml0LCBmaW5hbFVuaXQpIHtcbiAgICBpZiAob3JpZ2luYWxVbml0ID09PSB2b2lkIDApIHsgb3JpZ2luYWxVbml0ID0gXCJtZXRlcnNcIjsgfVxuICAgIGlmIChmaW5hbFVuaXQgPT09IHZvaWQgMCkgeyBmaW5hbFVuaXQgPSBcImtpbG9tZXRlcnNcIjsgfVxuICAgIGlmICghKGFyZWEgPj0gMCkpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiYXJlYSBtdXN0IGJlIGEgcG9zaXRpdmUgbnVtYmVyXCIpO1xuICAgIH1cbiAgICB2YXIgc3RhcnRGYWN0b3IgPSBleHBvcnRzLmFyZWFGYWN0b3JzW29yaWdpbmFsVW5pdF07XG4gICAgaWYgKCFzdGFydEZhY3Rvcikge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJpbnZhbGlkIG9yaWdpbmFsIHVuaXRzXCIpO1xuICAgIH1cbiAgICB2YXIgZmluYWxGYWN0b3IgPSBleHBvcnRzLmFyZWFGYWN0b3JzW2ZpbmFsVW5pdF07XG4gICAgaWYgKCFmaW5hbEZhY3Rvcikge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJpbnZhbGlkIGZpbmFsIHVuaXRzXCIpO1xuICAgIH1cbiAgICByZXR1cm4gKGFyZWEgLyBzdGFydEZhY3RvcikgKiBmaW5hbEZhY3Rvcjtcbn1cbmV4cG9ydHMuY29udmVydEFyZWEgPSBjb252ZXJ0QXJlYTtcbi8qKlxuICogaXNOdW1iZXJcbiAqXG4gKiBAcGFyYW0geyp9IG51bSBOdW1iZXIgdG8gdmFsaWRhdGVcbiAqIEByZXR1cm5zIHtib29sZWFufSB0cnVlL2ZhbHNlXG4gKiBAZXhhbXBsZVxuICogdHVyZi5pc051bWJlcigxMjMpXG4gKiAvLz10cnVlXG4gKiB0dXJmLmlzTnVtYmVyKCdmb28nKVxuICogLy89ZmFsc2VcbiAqL1xuZnVuY3Rpb24gaXNOdW1iZXIobnVtKSB7XG4gICAgcmV0dXJuICFpc05hTihudW0pICYmIG51bSAhPT0gbnVsbCAmJiAhQXJyYXkuaXNBcnJheShudW0pO1xufVxuZXhwb3J0cy5pc051bWJlciA9IGlzTnVtYmVyO1xuLyoqXG4gKiBpc09iamVjdFxuICpcbiAqIEBwYXJhbSB7Kn0gaW5wdXQgdmFyaWFibGUgdG8gdmFsaWRhdGVcbiAqIEByZXR1cm5zIHtib29sZWFufSB0cnVlL2ZhbHNlXG4gKiBAZXhhbXBsZVxuICogdHVyZi5pc09iamVjdCh7ZWxldmF0aW9uOiAxMH0pXG4gKiAvLz10cnVlXG4gKiB0dXJmLmlzT2JqZWN0KCdmb28nKVxuICogLy89ZmFsc2VcbiAqL1xuZnVuY3Rpb24gaXNPYmplY3QoaW5wdXQpIHtcbiAgICByZXR1cm4gISFpbnB1dCAmJiBpbnB1dC5jb25zdHJ1Y3RvciA9PT0gT2JqZWN0O1xufVxuZXhwb3J0cy5pc09iamVjdCA9IGlzT2JqZWN0O1xuLyoqXG4gKiBWYWxpZGF0ZSBCQm94XG4gKlxuICogQHByaXZhdGVcbiAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyPn0gYmJveCBCQm94IHRvIHZhbGlkYXRlXG4gKiBAcmV0dXJucyB7dm9pZH1cbiAqIEB0aHJvd3MgRXJyb3IgaWYgQkJveCBpcyBub3QgdmFsaWRcbiAqIEBleGFtcGxlXG4gKiB2YWxpZGF0ZUJCb3goWy0xODAsIC00MCwgMTEwLCA1MF0pXG4gKiAvLz1PS1xuICogdmFsaWRhdGVCQm94KFstMTgwLCAtNDBdKVxuICogLy89RXJyb3JcbiAqIHZhbGlkYXRlQkJveCgnRm9vJylcbiAqIC8vPUVycm9yXG4gKiB2YWxpZGF0ZUJCb3goNSlcbiAqIC8vPUVycm9yXG4gKiB2YWxpZGF0ZUJCb3gobnVsbClcbiAqIC8vPUVycm9yXG4gKiB2YWxpZGF0ZUJCb3godW5kZWZpbmVkKVxuICogLy89RXJyb3JcbiAqL1xuZnVuY3Rpb24gdmFsaWRhdGVCQm94KGJib3gpIHtcbiAgICBpZiAoIWJib3gpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiYmJveCBpcyByZXF1aXJlZFwiKTtcbiAgICB9XG4gICAgaWYgKCFBcnJheS5pc0FycmF5KGJib3gpKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImJib3ggbXVzdCBiZSBhbiBBcnJheVwiKTtcbiAgICB9XG4gICAgaWYgKGJib3gubGVuZ3RoICE9PSA0ICYmIGJib3gubGVuZ3RoICE9PSA2KSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImJib3ggbXVzdCBiZSBhbiBBcnJheSBvZiA0IG9yIDYgbnVtYmVyc1wiKTtcbiAgICB9XG4gICAgYmJveC5mb3JFYWNoKGZ1bmN0aW9uIChudW0pIHtcbiAgICAgICAgaWYgKCFpc051bWJlcihudW0pKSB7XG4gICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJiYm94IG11c3Qgb25seSBjb250YWluIG51bWJlcnNcIik7XG4gICAgICAgIH1cbiAgICB9KTtcbn1cbmV4cG9ydHMudmFsaWRhdGVCQm94ID0gdmFsaWRhdGVCQm94O1xuLyoqXG4gKiBWYWxpZGF0ZSBJZFxuICpcbiAqIEBwcml2YXRlXG4gKiBAcGFyYW0ge3N0cmluZ3xudW1iZXJ9IGlkIElkIHRvIHZhbGlkYXRlXG4gKiBAcmV0dXJucyB7dm9pZH1cbiAqIEB0aHJvd3MgRXJyb3IgaWYgSWQgaXMgbm90IHZhbGlkXG4gKiBAZXhhbXBsZVxuICogdmFsaWRhdGVJZChbLTE4MCwgLTQwLCAxMTAsIDUwXSlcbiAqIC8vPUVycm9yXG4gKiB2YWxpZGF0ZUlkKFstMTgwLCAtNDBdKVxuICogLy89RXJyb3JcbiAqIHZhbGlkYXRlSWQoJ0ZvbycpXG4gKiAvLz1PS1xuICogdmFsaWRhdGVJZCg1KVxuICogLy89T0tcbiAqIHZhbGlkYXRlSWQobnVsbClcbiAqIC8vPUVycm9yXG4gKiB2YWxpZGF0ZUlkKHVuZGVmaW5lZClcbiAqIC8vPUVycm9yXG4gKi9cbmZ1bmN0aW9uIHZhbGlkYXRlSWQoaWQpIHtcbiAgICBpZiAoIWlkKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImlkIGlzIHJlcXVpcmVkXCIpO1xuICAgIH1cbiAgICBpZiAoW1wic3RyaW5nXCIsIFwibnVtYmVyXCJdLmluZGV4T2YodHlwZW9mIGlkKSA9PT0gLTEpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiaWQgbXVzdCBiZSBhIG51bWJlciBvciBhIHN0cmluZ1wiKTtcbiAgICB9XG59XG5leHBvcnRzLnZhbGlkYXRlSWQgPSB2YWxpZGF0ZUlkO1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG52YXIgaGVscGVyc18xID0gcmVxdWlyZShcIkB0dXJmL2hlbHBlcnNcIik7XG4vKipcbiAqIFVud3JhcCBhIGNvb3JkaW5hdGUgZnJvbSBhIFBvaW50IEZlYXR1cmUsIEdlb21ldHJ5IG9yIGEgc2luZ2xlIGNvb3JkaW5hdGUuXG4gKlxuICogQG5hbWUgZ2V0Q29vcmRcbiAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyPnxHZW9tZXRyeTxQb2ludD58RmVhdHVyZTxQb2ludD59IGNvb3JkIEdlb0pTT04gUG9pbnQgb3IgYW4gQXJyYXkgb2YgbnVtYmVyc1xuICogQHJldHVybnMge0FycmF5PG51bWJlcj59IGNvb3JkaW5hdGVzXG4gKiBAZXhhbXBsZVxuICogdmFyIHB0ID0gdHVyZi5wb2ludChbMTAsIDEwXSk7XG4gKlxuICogdmFyIGNvb3JkID0gdHVyZi5nZXRDb29yZChwdCk7XG4gKiAvLz0gWzEwLCAxMF1cbiAqL1xuZnVuY3Rpb24gZ2V0Q29vcmQoY29vcmQpIHtcbiAgICBpZiAoIWNvb3JkKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkIGlzIHJlcXVpcmVkXCIpO1xuICAgIH1cbiAgICBpZiAoIUFycmF5LmlzQXJyYXkoY29vcmQpKSB7XG4gICAgICAgIGlmIChjb29yZC50eXBlID09PSBcIkZlYXR1cmVcIiAmJlxuICAgICAgICAgICAgY29vcmQuZ2VvbWV0cnkgIT09IG51bGwgJiZcbiAgICAgICAgICAgIGNvb3JkLmdlb21ldHJ5LnR5cGUgPT09IFwiUG9pbnRcIikge1xuICAgICAgICAgICAgcmV0dXJuIGNvb3JkLmdlb21ldHJ5LmNvb3JkaW5hdGVzO1xuICAgICAgICB9XG4gICAgICAgIGlmIChjb29yZC50eXBlID09PSBcIlBvaW50XCIpIHtcbiAgICAgICAgICAgIHJldHVybiBjb29yZC5jb29yZGluYXRlcztcbiAgICAgICAgfVxuICAgIH1cbiAgICBpZiAoQXJyYXkuaXNBcnJheShjb29yZCkgJiZcbiAgICAgICAgY29vcmQubGVuZ3RoID49IDIgJiZcbiAgICAgICAgIUFycmF5LmlzQXJyYXkoY29vcmRbMF0pICYmXG4gICAgICAgICFBcnJheS5pc0FycmF5KGNvb3JkWzFdKSkge1xuICAgICAgICByZXR1cm4gY29vcmQ7XG4gICAgfVxuICAgIHRocm93IG5ldyBFcnJvcihcImNvb3JkIG11c3QgYmUgR2VvSlNPTiBQb2ludCBvciBhbiBBcnJheSBvZiBudW1iZXJzXCIpO1xufVxuZXhwb3J0cy5nZXRDb29yZCA9IGdldENvb3JkO1xuLyoqXG4gKiBVbndyYXAgY29vcmRpbmF0ZXMgZnJvbSBhIEZlYXR1cmUsIEdlb21ldHJ5IE9iamVjdCBvciBhbiBBcnJheVxuICpcbiAqIEBuYW1lIGdldENvb3Jkc1xuICogQHBhcmFtIHtBcnJheTxhbnk+fEdlb21ldHJ5fEZlYXR1cmV9IGNvb3JkcyBGZWF0dXJlLCBHZW9tZXRyeSBPYmplY3Qgb3IgYW4gQXJyYXlcbiAqIEByZXR1cm5zIHtBcnJheTxhbnk+fSBjb29yZGluYXRlc1xuICogQGV4YW1wbGVcbiAqIHZhciBwb2x5ID0gdHVyZi5wb2x5Z29uKFtbWzExOS4zMiwgLTguN10sIFsxMTkuNTUsIC04LjY5XSwgWzExOS41MSwgLTguNTRdLCBbMTE5LjMyLCAtOC43XV1dKTtcbiAqXG4gKiB2YXIgY29vcmRzID0gdHVyZi5nZXRDb29yZHMocG9seSk7XG4gKiAvLz0gW1tbMTE5LjMyLCAtOC43XSwgWzExOS41NSwgLTguNjldLCBbMTE5LjUxLCAtOC41NF0sIFsxMTkuMzIsIC04LjddXV1cbiAqL1xuZnVuY3Rpb24gZ2V0Q29vcmRzKGNvb3Jkcykge1xuICAgIGlmIChBcnJheS5pc0FycmF5KGNvb3JkcykpIHtcbiAgICAgICAgcmV0dXJuIGNvb3JkcztcbiAgICB9XG4gICAgLy8gRmVhdHVyZVxuICAgIGlmIChjb29yZHMudHlwZSA9PT0gXCJGZWF0dXJlXCIpIHtcbiAgICAgICAgaWYgKGNvb3Jkcy5nZW9tZXRyeSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIGNvb3Jkcy5nZW9tZXRyeS5jb29yZGluYXRlcztcbiAgICAgICAgfVxuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgLy8gR2VvbWV0cnlcbiAgICAgICAgaWYgKGNvb3Jkcy5jb29yZGluYXRlcykge1xuICAgICAgICAgICAgcmV0dXJuIGNvb3Jkcy5jb29yZGluYXRlcztcbiAgICAgICAgfVxuICAgIH1cbiAgICB0aHJvdyBuZXcgRXJyb3IoXCJjb29yZHMgbXVzdCBiZSBHZW9KU09OIEZlYXR1cmUsIEdlb21ldHJ5IE9iamVjdCBvciBhbiBBcnJheVwiKTtcbn1cbmV4cG9ydHMuZ2V0Q29vcmRzID0gZ2V0Q29vcmRzO1xuLyoqXG4gKiBDaGVja3MgaWYgY29vcmRpbmF0ZXMgY29udGFpbnMgYSBudW1iZXJcbiAqXG4gKiBAbmFtZSBjb250YWluc051bWJlclxuICogQHBhcmFtIHtBcnJheTxhbnk+fSBjb29yZGluYXRlcyBHZW9KU09OIENvb3JkaW5hdGVzXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn0gdHJ1ZSBpZiBBcnJheSBjb250YWlucyBhIG51bWJlclxuICovXG5mdW5jdGlvbiBjb250YWluc051bWJlcihjb29yZGluYXRlcykge1xuICAgIGlmIChjb29yZGluYXRlcy5sZW5ndGggPiAxICYmXG4gICAgICAgIGhlbHBlcnNfMS5pc051bWJlcihjb29yZGluYXRlc1swXSkgJiZcbiAgICAgICAgaGVscGVyc18xLmlzTnVtYmVyKGNvb3JkaW5hdGVzWzFdKSkge1xuICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICB9XG4gICAgaWYgKEFycmF5LmlzQXJyYXkoY29vcmRpbmF0ZXNbMF0pICYmIGNvb3JkaW5hdGVzWzBdLmxlbmd0aCkge1xuICAgICAgICByZXR1cm4gY29udGFpbnNOdW1iZXIoY29vcmRpbmF0ZXNbMF0pO1xuICAgIH1cbiAgICB0aHJvdyBuZXcgRXJyb3IoXCJjb29yZGluYXRlcyBtdXN0IG9ubHkgY29udGFpbiBudW1iZXJzXCIpO1xufVxuZXhwb3J0cy5jb250YWluc051bWJlciA9IGNvbnRhaW5zTnVtYmVyO1xuLyoqXG4gKiBFbmZvcmNlIGV4cGVjdGF0aW9ucyBhYm91dCB0eXBlcyBvZiBHZW9KU09OIG9iamVjdHMgZm9yIFR1cmYuXG4gKlxuICogQG5hbWUgZ2VvanNvblR5cGVcbiAqIEBwYXJhbSB7R2VvSlNPTn0gdmFsdWUgYW55IEdlb0pTT04gb2JqZWN0XG4gKiBAcGFyYW0ge3N0cmluZ30gdHlwZSBleHBlY3RlZCBHZW9KU09OIHR5cGVcbiAqIEBwYXJhbSB7c3RyaW5nfSBuYW1lIG5hbWUgb2YgY2FsbGluZyBmdW5jdGlvblxuICogQHRocm93cyB7RXJyb3J9IGlmIHZhbHVlIGlzIG5vdCB0aGUgZXhwZWN0ZWQgdHlwZS5cbiAqL1xuZnVuY3Rpb24gZ2VvanNvblR5cGUodmFsdWUsIHR5cGUsIG5hbWUpIHtcbiAgICBpZiAoIXR5cGUgfHwgIW5hbWUpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwidHlwZSBhbmQgbmFtZSByZXF1aXJlZFwiKTtcbiAgICB9XG4gICAgaWYgKCF2YWx1ZSB8fCB2YWx1ZS50eXBlICE9PSB0eXBlKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIkludmFsaWQgaW5wdXQgdG8gXCIgK1xuICAgICAgICAgICAgbmFtZSArXG4gICAgICAgICAgICBcIjogbXVzdCBiZSBhIFwiICtcbiAgICAgICAgICAgIHR5cGUgK1xuICAgICAgICAgICAgXCIsIGdpdmVuIFwiICtcbiAgICAgICAgICAgIHZhbHVlLnR5cGUpO1xuICAgIH1cbn1cbmV4cG9ydHMuZ2VvanNvblR5cGUgPSBnZW9qc29uVHlwZTtcbi8qKlxuICogRW5mb3JjZSBleHBlY3RhdGlvbnMgYWJvdXQgdHlwZXMgb2Yge0BsaW5rIEZlYXR1cmV9IGlucHV0cyBmb3IgVHVyZi5cbiAqIEludGVybmFsbHkgdGhpcyB1c2VzIHtAbGluayBnZW9qc29uVHlwZX0gdG8ganVkZ2UgZ2VvbWV0cnkgdHlwZXMuXG4gKlxuICogQG5hbWUgZmVhdHVyZU9mXG4gKiBAcGFyYW0ge0ZlYXR1cmV9IGZlYXR1cmUgYSBmZWF0dXJlIHdpdGggYW4gZXhwZWN0ZWQgZ2VvbWV0cnkgdHlwZVxuICogQHBhcmFtIHtzdHJpbmd9IHR5cGUgZXhwZWN0ZWQgR2VvSlNPTiB0eXBlXG4gKiBAcGFyYW0ge3N0cmluZ30gbmFtZSBuYW1lIG9mIGNhbGxpbmcgZnVuY3Rpb25cbiAqIEB0aHJvd3Mge0Vycm9yfSBlcnJvciBpZiB2YWx1ZSBpcyBub3QgdGhlIGV4cGVjdGVkIHR5cGUuXG4gKi9cbmZ1bmN0aW9uIGZlYXR1cmVPZihmZWF0dXJlLCB0eXBlLCBuYW1lKSB7XG4gICAgaWYgKCFmZWF0dXJlKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIk5vIGZlYXR1cmUgcGFzc2VkXCIpO1xuICAgIH1cbiAgICBpZiAoIW5hbWUpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiLmZlYXR1cmVPZigpIHJlcXVpcmVzIGEgbmFtZVwiKTtcbiAgICB9XG4gICAgaWYgKCFmZWF0dXJlIHx8IGZlYXR1cmUudHlwZSAhPT0gXCJGZWF0dXJlXCIgfHwgIWZlYXR1cmUuZ2VvbWV0cnkpIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFwiSW52YWxpZCBpbnB1dCB0byBcIiArIG5hbWUgKyBcIiwgRmVhdHVyZSB3aXRoIGdlb21ldHJ5IHJlcXVpcmVkXCIpO1xuICAgIH1cbiAgICBpZiAoIWZlYXR1cmUuZ2VvbWV0cnkgfHwgZmVhdHVyZS5nZW9tZXRyeS50eXBlICE9PSB0eXBlKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIkludmFsaWQgaW5wdXQgdG8gXCIgK1xuICAgICAgICAgICAgbmFtZSArXG4gICAgICAgICAgICBcIjogbXVzdCBiZSBhIFwiICtcbiAgICAgICAgICAgIHR5cGUgK1xuICAgICAgICAgICAgXCIsIGdpdmVuIFwiICtcbiAgICAgICAgICAgIGZlYXR1cmUuZ2VvbWV0cnkudHlwZSk7XG4gICAgfVxufVxuZXhwb3J0cy5mZWF0dXJlT2YgPSBmZWF0dXJlT2Y7XG4vKipcbiAqIEVuZm9yY2UgZXhwZWN0YXRpb25zIGFib3V0IHR5cGVzIG9mIHtAbGluayBGZWF0dXJlQ29sbGVjdGlvbn0gaW5wdXRzIGZvciBUdXJmLlxuICogSW50ZXJuYWxseSB0aGlzIHVzZXMge0BsaW5rIGdlb2pzb25UeXBlfSB0byBqdWRnZSBnZW9tZXRyeSB0eXBlcy5cbiAqXG4gKiBAbmFtZSBjb2xsZWN0aW9uT2ZcbiAqIEBwYXJhbSB7RmVhdHVyZUNvbGxlY3Rpb259IGZlYXR1cmVDb2xsZWN0aW9uIGEgRmVhdHVyZUNvbGxlY3Rpb24gZm9yIHdoaWNoIGZlYXR1cmVzIHdpbGwgYmUganVkZ2VkXG4gKiBAcGFyYW0ge3N0cmluZ30gdHlwZSBleHBlY3RlZCBHZW9KU09OIHR5cGVcbiAqIEBwYXJhbSB7c3RyaW5nfSBuYW1lIG5hbWUgb2YgY2FsbGluZyBmdW5jdGlvblxuICogQHRocm93cyB7RXJyb3J9IGlmIHZhbHVlIGlzIG5vdCB0aGUgZXhwZWN0ZWQgdHlwZS5cbiAqL1xuZnVuY3Rpb24gY29sbGVjdGlvbk9mKGZlYXR1cmVDb2xsZWN0aW9uLCB0eXBlLCBuYW1lKSB7XG4gICAgaWYgKCFmZWF0dXJlQ29sbGVjdGlvbikge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJObyBmZWF0dXJlQ29sbGVjdGlvbiBwYXNzZWRcIik7XG4gICAgfVxuICAgIGlmICghbmFtZSkge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCIuY29sbGVjdGlvbk9mKCkgcmVxdWlyZXMgYSBuYW1lXCIpO1xuICAgIH1cbiAgICBpZiAoIWZlYXR1cmVDb2xsZWN0aW9uIHx8IGZlYXR1cmVDb2xsZWN0aW9uLnR5cGUgIT09IFwiRmVhdHVyZUNvbGxlY3Rpb25cIikge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJJbnZhbGlkIGlucHV0IHRvIFwiICsgbmFtZSArIFwiLCBGZWF0dXJlQ29sbGVjdGlvbiByZXF1aXJlZFwiKTtcbiAgICB9XG4gICAgZm9yICh2YXIgX2kgPSAwLCBfYSA9IGZlYXR1cmVDb2xsZWN0aW9uLmZlYXR1cmVzOyBfaSA8IF9hLmxlbmd0aDsgX2krKykge1xuICAgICAgICB2YXIgZmVhdHVyZSA9IF9hW19pXTtcbiAgICAgICAgaWYgKCFmZWF0dXJlIHx8IGZlYXR1cmUudHlwZSAhPT0gXCJGZWF0dXJlXCIgfHwgIWZlYXR1cmUuZ2VvbWV0cnkpIHtcbiAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcihcIkludmFsaWQgaW5wdXQgdG8gXCIgKyBuYW1lICsgXCIsIEZlYXR1cmUgd2l0aCBnZW9tZXRyeSByZXF1aXJlZFwiKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoIWZlYXR1cmUuZ2VvbWV0cnkgfHwgZmVhdHVyZS5nZW9tZXRyeS50eXBlICE9PSB0eXBlKSB7XG4gICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJJbnZhbGlkIGlucHV0IHRvIFwiICtcbiAgICAgICAgICAgICAgICBuYW1lICtcbiAgICAgICAgICAgICAgICBcIjogbXVzdCBiZSBhIFwiICtcbiAgICAgICAgICAgICAgICB0eXBlICtcbiAgICAgICAgICAgICAgICBcIiwgZ2l2ZW4gXCIgK1xuICAgICAgICAgICAgICAgIGZlYXR1cmUuZ2VvbWV0cnkudHlwZSk7XG4gICAgICAgIH1cbiAgICB9XG59XG5leHBvcnRzLmNvbGxlY3Rpb25PZiA9IGNvbGxlY3Rpb25PZjtcbi8qKlxuICogR2V0IEdlb21ldHJ5IGZyb20gRmVhdHVyZSBvciBHZW9tZXRyeSBPYmplY3RcbiAqXG4gKiBAcGFyYW0ge0ZlYXR1cmV8R2VvbWV0cnl9IGdlb2pzb24gR2VvSlNPTiBGZWF0dXJlIG9yIEdlb21ldHJ5IE9iamVjdFxuICogQHJldHVybnMge0dlb21ldHJ5fG51bGx9IEdlb0pTT04gR2VvbWV0cnkgT2JqZWN0XG4gKiBAdGhyb3dzIHtFcnJvcn0gaWYgZ2VvanNvbiBpcyBub3QgYSBGZWF0dXJlIG9yIEdlb21ldHJ5IE9iamVjdFxuICogQGV4YW1wbGVcbiAqIHZhciBwb2ludCA9IHtcbiAqICAgXCJ0eXBlXCI6IFwiRmVhdHVyZVwiLFxuICogICBcInByb3BlcnRpZXNcIjoge30sXG4gKiAgIFwiZ2VvbWV0cnlcIjoge1xuICogICAgIFwidHlwZVwiOiBcIlBvaW50XCIsXG4gKiAgICAgXCJjb29yZGluYXRlc1wiOiBbMTEwLCA0MF1cbiAqICAgfVxuICogfVxuICogdmFyIGdlb20gPSB0dXJmLmdldEdlb20ocG9pbnQpXG4gKiAvLz17XCJ0eXBlXCI6IFwiUG9pbnRcIiwgXCJjb29yZGluYXRlc1wiOiBbMTEwLCA0MF19XG4gKi9cbmZ1bmN0aW9uIGdldEdlb20oZ2VvanNvbikge1xuICAgIGlmIChnZW9qc29uLnR5cGUgPT09IFwiRmVhdHVyZVwiKSB7XG4gICAgICAgIHJldHVybiBnZW9qc29uLmdlb21ldHJ5O1xuICAgIH1cbiAgICByZXR1cm4gZ2VvanNvbjtcbn1cbmV4cG9ydHMuZ2V0R2VvbSA9IGdldEdlb207XG4vKipcbiAqIEdldCBHZW9KU09OIG9iamVjdCdzIHR5cGUsIEdlb21ldHJ5IHR5cGUgaXMgcHJpb3JpdGl6ZS5cbiAqXG4gKiBAcGFyYW0ge0dlb0pTT059IGdlb2pzb24gR2VvSlNPTiBvYmplY3RcbiAqIEBwYXJhbSB7c3RyaW5nfSBbbmFtZT1cImdlb2pzb25cIl0gbmFtZSBvZiB0aGUgdmFyaWFibGUgdG8gZGlzcGxheSBpbiBlcnJvciBtZXNzYWdlICh1bnVzZWQpXG4gKiBAcmV0dXJucyB7c3RyaW5nfSBHZW9KU09OIHR5cGVcbiAqIEBleGFtcGxlXG4gKiB2YXIgcG9pbnQgPSB7XG4gKiAgIFwidHlwZVwiOiBcIkZlYXR1cmVcIixcbiAqICAgXCJwcm9wZXJ0aWVzXCI6IHt9LFxuICogICBcImdlb21ldHJ5XCI6IHtcbiAqICAgICBcInR5cGVcIjogXCJQb2ludFwiLFxuICogICAgIFwiY29vcmRpbmF0ZXNcIjogWzExMCwgNDBdXG4gKiAgIH1cbiAqIH1cbiAqIHZhciBnZW9tID0gdHVyZi5nZXRUeXBlKHBvaW50KVxuICogLy89XCJQb2ludFwiXG4gKi9cbmZ1bmN0aW9uIGdldFR5cGUoZ2VvanNvbiwgX25hbWUpIHtcbiAgICBpZiAoZ2VvanNvbi50eXBlID09PSBcIkZlYXR1cmVDb2xsZWN0aW9uXCIpIHtcbiAgICAgICAgcmV0dXJuIFwiRmVhdHVyZUNvbGxlY3Rpb25cIjtcbiAgICB9XG4gICAgaWYgKGdlb2pzb24udHlwZSA9PT0gXCJHZW9tZXRyeUNvbGxlY3Rpb25cIikge1xuICAgICAgICByZXR1cm4gXCJHZW9tZXRyeUNvbGxlY3Rpb25cIjtcbiAgICB9XG4gICAgaWYgKGdlb2pzb24udHlwZSA9PT0gXCJGZWF0dXJlXCIgJiYgZ2VvanNvbi5nZW9tZXRyeSAhPT0gbnVsbCkge1xuICAgICAgICByZXR1cm4gZ2VvanNvbi5nZW9tZXRyeS50eXBlO1xuICAgIH1cbiAgICByZXR1cm4gZ2VvanNvbi50eXBlO1xufVxuZXhwb3J0cy5nZXRUeXBlID0gZ2V0VHlwZTtcbiIsIid1c2Ugc3RyaWN0JztcblxubW9kdWxlLmV4cG9ydHMgPSBkZWNvZGU7XG5cbnZhciBrZXlzLCB2YWx1ZXMsIGxlbmd0aHMsIGRpbSwgZTtcblxudmFyIGdlb21ldHJ5VHlwZXMgPSBbXG4gICAgJ1BvaW50JywgJ011bHRpUG9pbnQnLCAnTGluZVN0cmluZycsICdNdWx0aUxpbmVTdHJpbmcnLFxuICAgICdQb2x5Z29uJywgJ011bHRpUG9seWdvbicsICdHZW9tZXRyeUNvbGxlY3Rpb24nXTtcblxuZnVuY3Rpb24gZGVjb2RlKHBiZikge1xuICAgIGRpbSA9IDI7XG4gICAgZSA9IE1hdGgucG93KDEwLCA2KTtcbiAgICBsZW5ndGhzID0gbnVsbDtcblxuICAgIGtleXMgPSBbXTtcbiAgICB2YWx1ZXMgPSBbXTtcbiAgICB2YXIgb2JqID0gcGJmLnJlYWRGaWVsZHMocmVhZERhdGFGaWVsZCwge30pO1xuICAgIGtleXMgPSBudWxsO1xuXG4gICAgcmV0dXJuIG9iajtcbn1cblxuZnVuY3Rpb24gcmVhZERhdGFGaWVsZCh0YWcsIG9iaiwgcGJmKSB7XG4gICAgaWYgKHRhZyA9PT0gMSkga2V5cy5wdXNoKHBiZi5yZWFkU3RyaW5nKCkpO1xuICAgIGVsc2UgaWYgKHRhZyA9PT0gMikgZGltID0gcGJmLnJlYWRWYXJpbnQoKTtcbiAgICBlbHNlIGlmICh0YWcgPT09IDMpIGUgPSBNYXRoLnBvdygxMCwgcGJmLnJlYWRWYXJpbnQoKSk7XG5cbiAgICBlbHNlIGlmICh0YWcgPT09IDQpIHJlYWRGZWF0dXJlQ29sbGVjdGlvbihwYmYsIG9iaik7XG4gICAgZWxzZSBpZiAodGFnID09PSA1KSByZWFkRmVhdHVyZShwYmYsIG9iaik7XG4gICAgZWxzZSBpZiAodGFnID09PSA2KSByZWFkR2VvbWV0cnkocGJmLCBvYmopO1xufVxuXG5mdW5jdGlvbiByZWFkRmVhdHVyZUNvbGxlY3Rpb24ocGJmLCBvYmopIHtcbiAgICBvYmoudHlwZSA9ICdGZWF0dXJlQ29sbGVjdGlvbic7XG4gICAgb2JqLmZlYXR1cmVzID0gW107XG4gICAgcmV0dXJuIHBiZi5yZWFkTWVzc2FnZShyZWFkRmVhdHVyZUNvbGxlY3Rpb25GaWVsZCwgb2JqKTtcbn1cblxuZnVuY3Rpb24gcmVhZEZlYXR1cmUocGJmLCBmZWF0dXJlKSB7XG4gICAgZmVhdHVyZS50eXBlID0gJ0ZlYXR1cmUnO1xuICAgIHZhciBmID0gcGJmLnJlYWRNZXNzYWdlKHJlYWRGZWF0dXJlRmllbGQsIGZlYXR1cmUpO1xuICAgIGlmICghKCdnZW9tZXRyeScgaW4gZikpIGYuZ2VvbWV0cnkgPSBudWxsO1xuICAgIHJldHVybiBmO1xufVxuXG5mdW5jdGlvbiByZWFkR2VvbWV0cnkocGJmLCBnZW9tKSB7XG4gICAgZ2VvbS50eXBlID0gJ1BvaW50JztcbiAgICByZXR1cm4gcGJmLnJlYWRNZXNzYWdlKHJlYWRHZW9tZXRyeUZpZWxkLCBnZW9tKTtcbn1cblxuZnVuY3Rpb24gcmVhZEZlYXR1cmVDb2xsZWN0aW9uRmllbGQodGFnLCBvYmosIHBiZikge1xuICAgIGlmICh0YWcgPT09IDEpIG9iai5mZWF0dXJlcy5wdXNoKHJlYWRGZWF0dXJlKHBiZiwge30pKTtcblxuICAgIGVsc2UgaWYgKHRhZyA9PT0gMTMpIHZhbHVlcy5wdXNoKHJlYWRWYWx1ZShwYmYpKTtcbiAgICBlbHNlIGlmICh0YWcgPT09IDE1KSByZWFkUHJvcHMocGJmLCBvYmopO1xufVxuXG5mdW5jdGlvbiByZWFkRmVhdHVyZUZpZWxkKHRhZywgZmVhdHVyZSwgcGJmKSB7XG4gICAgaWYgKHRhZyA9PT0gMSkgZmVhdHVyZS5nZW9tZXRyeSA9IHJlYWRHZW9tZXRyeShwYmYsIHt9KTtcblxuICAgIGVsc2UgaWYgKHRhZyA9PT0gMTEpIGZlYXR1cmUuaWQgPSBwYmYucmVhZFN0cmluZygpO1xuICAgIGVsc2UgaWYgKHRhZyA9PT0gMTIpIGZlYXR1cmUuaWQgPSBwYmYucmVhZFNWYXJpbnQoKTtcblxuICAgIGVsc2UgaWYgKHRhZyA9PT0gMTMpIHZhbHVlcy5wdXNoKHJlYWRWYWx1ZShwYmYpKTtcbiAgICBlbHNlIGlmICh0YWcgPT09IDE0KSBmZWF0dXJlLnByb3BlcnRpZXMgPSByZWFkUHJvcHMocGJmLCB7fSk7XG4gICAgZWxzZSBpZiAodGFnID09PSAxNSkgcmVhZFByb3BzKHBiZiwgZmVhdHVyZSk7XG59XG5cbmZ1bmN0aW9uIHJlYWRHZW9tZXRyeUZpZWxkKHRhZywgZ2VvbSwgcGJmKSB7XG4gICAgaWYgKHRhZyA9PT0gMSkgZ2VvbS50eXBlID0gZ2VvbWV0cnlUeXBlc1twYmYucmVhZFZhcmludCgpXTtcblxuICAgIGVsc2UgaWYgKHRhZyA9PT0gMikgbGVuZ3RocyA9IHBiZi5yZWFkUGFja2VkVmFyaW50KCk7XG4gICAgZWxzZSBpZiAodGFnID09PSAzKSByZWFkQ29vcmRzKGdlb20sIHBiZiwgZ2VvbS50eXBlKTtcbiAgICBlbHNlIGlmICh0YWcgPT09IDQpIHtcbiAgICAgICAgZ2VvbS5nZW9tZXRyaWVzID0gZ2VvbS5nZW9tZXRyaWVzIHx8IFtdO1xuICAgICAgICBnZW9tLmdlb21ldHJpZXMucHVzaChyZWFkR2VvbWV0cnkocGJmLCB7fSkpO1xuICAgIH1cbiAgICBlbHNlIGlmICh0YWcgPT09IDEzKSB2YWx1ZXMucHVzaChyZWFkVmFsdWUocGJmKSk7XG4gICAgZWxzZSBpZiAodGFnID09PSAxNSkgcmVhZFByb3BzKHBiZiwgZ2VvbSk7XG59XG5cbmZ1bmN0aW9uIHJlYWRDb29yZHMoZ2VvbSwgcGJmLCB0eXBlKSB7XG4gICAgaWYgKHR5cGUgPT09ICdQb2ludCcpIGdlb20uY29vcmRpbmF0ZXMgPSByZWFkUG9pbnQocGJmKTtcbiAgICBlbHNlIGlmICh0eXBlID09PSAnTXVsdGlQb2ludCcpIGdlb20uY29vcmRpbmF0ZXMgPSByZWFkTGluZShwYmYsIHRydWUpO1xuICAgIGVsc2UgaWYgKHR5cGUgPT09ICdMaW5lU3RyaW5nJykgZ2VvbS5jb29yZGluYXRlcyA9IHJlYWRMaW5lKHBiZik7XG4gICAgZWxzZSBpZiAodHlwZSA9PT0gJ011bHRpTGluZVN0cmluZycpIGdlb20uY29vcmRpbmF0ZXMgPSByZWFkTXVsdGlMaW5lKHBiZik7XG4gICAgZWxzZSBpZiAodHlwZSA9PT0gJ1BvbHlnb24nKSBnZW9tLmNvb3JkaW5hdGVzID0gcmVhZE11bHRpTGluZShwYmYsIHRydWUpO1xuICAgIGVsc2UgaWYgKHR5cGUgPT09ICdNdWx0aVBvbHlnb24nKSBnZW9tLmNvb3JkaW5hdGVzID0gcmVhZE11bHRpUG9seWdvbihwYmYpO1xufVxuXG5mdW5jdGlvbiByZWFkVmFsdWUocGJmKSB7XG4gICAgdmFyIGVuZCA9IHBiZi5yZWFkVmFyaW50KCkgKyBwYmYucG9zLFxuICAgICAgICB2YWx1ZSA9IG51bGw7XG5cbiAgICB3aGlsZSAocGJmLnBvcyA8IGVuZCkge1xuICAgICAgICB2YXIgdmFsID0gcGJmLnJlYWRWYXJpbnQoKSxcbiAgICAgICAgICAgIHRhZyA9IHZhbCA+PiAzO1xuXG4gICAgICAgIGlmICh0YWcgPT09IDEpIHZhbHVlID0gcGJmLnJlYWRTdHJpbmcoKTtcbiAgICAgICAgZWxzZSBpZiAodGFnID09PSAyKSB2YWx1ZSA9IHBiZi5yZWFkRG91YmxlKCk7XG4gICAgICAgIGVsc2UgaWYgKHRhZyA9PT0gMykgdmFsdWUgPSBwYmYucmVhZFZhcmludCgpO1xuICAgICAgICBlbHNlIGlmICh0YWcgPT09IDQpIHZhbHVlID0gLXBiZi5yZWFkVmFyaW50KCk7XG4gICAgICAgIGVsc2UgaWYgKHRhZyA9PT0gNSkgdmFsdWUgPSBwYmYucmVhZEJvb2xlYW4oKTtcbiAgICAgICAgZWxzZSBpZiAodGFnID09PSA2KSB2YWx1ZSA9IEpTT04ucGFyc2UocGJmLnJlYWRTdHJpbmcoKSk7XG4gICAgfVxuICAgIHJldHVybiB2YWx1ZTtcbn1cblxuZnVuY3Rpb24gcmVhZFByb3BzKHBiZiwgcHJvcHMpIHtcbiAgICB2YXIgZW5kID0gcGJmLnJlYWRWYXJpbnQoKSArIHBiZi5wb3M7XG4gICAgd2hpbGUgKHBiZi5wb3MgPCBlbmQpIHByb3BzW2tleXNbcGJmLnJlYWRWYXJpbnQoKV1dID0gdmFsdWVzW3BiZi5yZWFkVmFyaW50KCldO1xuICAgIHZhbHVlcyA9IFtdO1xuICAgIHJldHVybiBwcm9wcztcbn1cblxuZnVuY3Rpb24gcmVhZFBvaW50KHBiZikge1xuICAgIHZhciBlbmQgPSBwYmYucmVhZFZhcmludCgpICsgcGJmLnBvcyxcbiAgICAgICAgY29vcmRzID0gW107XG4gICAgd2hpbGUgKHBiZi5wb3MgPCBlbmQpIGNvb3Jkcy5wdXNoKHBiZi5yZWFkU1ZhcmludCgpIC8gZSk7XG4gICAgcmV0dXJuIGNvb3Jkcztcbn1cblxuZnVuY3Rpb24gcmVhZExpbmVQYXJ0KHBiZiwgZW5kLCBsZW4sIGNsb3NlZCkge1xuICAgIHZhciBpID0gMCxcbiAgICAgICAgY29vcmRzID0gW10sXG4gICAgICAgIHAsIGQ7XG5cbiAgICB2YXIgcHJldlAgPSBbXTtcbiAgICBmb3IgKGQgPSAwOyBkIDwgZGltOyBkKyspIHByZXZQW2RdID0gMDtcblxuICAgIHdoaWxlIChsZW4gPyBpIDwgbGVuIDogcGJmLnBvcyA8IGVuZCkge1xuICAgICAgICBwID0gW107XG4gICAgICAgIGZvciAoZCA9IDA7IGQgPCBkaW07IGQrKykge1xuICAgICAgICAgICAgcHJldlBbZF0gKz0gcGJmLnJlYWRTVmFyaW50KCk7XG4gICAgICAgICAgICBwW2RdID0gcHJldlBbZF0gLyBlO1xuICAgICAgICB9XG4gICAgICAgIGNvb3Jkcy5wdXNoKHApO1xuICAgICAgICBpKys7XG4gICAgfVxuICAgIGlmIChjbG9zZWQpIGNvb3Jkcy5wdXNoKGNvb3Jkc1swXSk7XG5cbiAgICByZXR1cm4gY29vcmRzO1xufVxuXG5mdW5jdGlvbiByZWFkTGluZShwYmYpIHtcbiAgICByZXR1cm4gcmVhZExpbmVQYXJ0KHBiZiwgcGJmLnJlYWRWYXJpbnQoKSArIHBiZi5wb3MpO1xufVxuXG5mdW5jdGlvbiByZWFkTXVsdGlMaW5lKHBiZiwgY2xvc2VkKSB7XG4gICAgdmFyIGVuZCA9IHBiZi5yZWFkVmFyaW50KCkgKyBwYmYucG9zO1xuICAgIGlmICghbGVuZ3RocykgcmV0dXJuIFtyZWFkTGluZVBhcnQocGJmLCBlbmQsIG51bGwsIGNsb3NlZCldO1xuXG4gICAgdmFyIGNvb3JkcyA9IFtdO1xuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbGVuZ3Rocy5sZW5ndGg7IGkrKykgY29vcmRzLnB1c2gocmVhZExpbmVQYXJ0KHBiZiwgZW5kLCBsZW5ndGhzW2ldLCBjbG9zZWQpKTtcbiAgICBsZW5ndGhzID0gbnVsbDtcbiAgICByZXR1cm4gY29vcmRzO1xufVxuXG5mdW5jdGlvbiByZWFkTXVsdGlQb2x5Z29uKHBiZikge1xuICAgIHZhciBlbmQgPSBwYmYucmVhZFZhcmludCgpICsgcGJmLnBvcztcbiAgICBpZiAoIWxlbmd0aHMpIHJldHVybiBbW3JlYWRMaW5lUGFydChwYmYsIGVuZCwgbnVsbCwgdHJ1ZSldXTtcblxuICAgIHZhciBjb29yZHMgPSBbXTtcbiAgICB2YXIgaiA9IDE7XG4gICAgZm9yICh2YXIgaSA9IDA7IGkgPCBsZW5ndGhzWzBdOyBpKyspIHtcbiAgICAgICAgdmFyIHJpbmdzID0gW107XG4gICAgICAgIGZvciAodmFyIGsgPSAwOyBrIDwgbGVuZ3Roc1tqXTsgaysrKSByaW5ncy5wdXNoKHJlYWRMaW5lUGFydChwYmYsIGVuZCwgbGVuZ3Roc1tqICsgMSArIGtdLCB0cnVlKSk7XG4gICAgICAgIGogKz0gbGVuZ3Roc1tqXSArIDE7XG4gICAgICAgIGNvb3Jkcy5wdXNoKHJpbmdzKTtcbiAgICB9XG4gICAgbGVuZ3RocyA9IG51bGw7XG4gICAgcmV0dXJuIGNvb3Jkcztcbn1cbiIsIid1c2Ugc3RyaWN0JztcblxubW9kdWxlLmV4cG9ydHMgPSBlbmNvZGU7XG5cbnZhciBrZXlzLCBrZXlzTnVtLCBrZXlzQXJyLCBkaW0sIGUsXG4gICAgbWF4UHJlY2lzaW9uID0gMWU2O1xuXG52YXIgZ2VvbWV0cnlUeXBlcyA9IHtcbiAgICAnUG9pbnQnOiAwLFxuICAgICdNdWx0aVBvaW50JzogMSxcbiAgICAnTGluZVN0cmluZyc6IDIsXG4gICAgJ011bHRpTGluZVN0cmluZyc6IDMsXG4gICAgJ1BvbHlnb24nOiA0LFxuICAgICdNdWx0aVBvbHlnb24nOiA1LFxuICAgICdHZW9tZXRyeUNvbGxlY3Rpb24nOiA2XG59O1xuXG5mdW5jdGlvbiBlbmNvZGUob2JqLCBwYmYpIHtcbiAgICBrZXlzID0ge307XG4gICAga2V5c0FyciA9IFtdO1xuICAgIGtleXNOdW0gPSAwO1xuICAgIGRpbSA9IDA7XG4gICAgZSA9IDE7XG5cbiAgICBhbmFseXplKG9iaik7XG5cbiAgICBlID0gTWF0aC5taW4oZSwgbWF4UHJlY2lzaW9uKTtcbiAgICB2YXIgcHJlY2lzaW9uID0gTWF0aC5jZWlsKE1hdGgubG9nKGUpIC8gTWF0aC5MTjEwKTtcblxuICAgIGZvciAodmFyIGkgPSAwOyBpIDwga2V5c0Fyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlU3RyaW5nRmllbGQoMSwga2V5c0FycltpXSk7XG4gICAgaWYgKGRpbSAhPT0gMikgcGJmLndyaXRlVmFyaW50RmllbGQoMiwgZGltKTtcbiAgICBpZiAocHJlY2lzaW9uICE9PSA2KSBwYmYud3JpdGVWYXJpbnRGaWVsZCgzLCBwcmVjaXNpb24pO1xuXG4gICAgaWYgKG9iai50eXBlID09PSAnRmVhdHVyZUNvbGxlY3Rpb24nKSBwYmYud3JpdGVNZXNzYWdlKDQsIHdyaXRlRmVhdHVyZUNvbGxlY3Rpb24sIG9iaik7XG4gICAgZWxzZSBpZiAob2JqLnR5cGUgPT09ICdGZWF0dXJlJykgcGJmLndyaXRlTWVzc2FnZSg1LCB3cml0ZUZlYXR1cmUsIG9iaik7XG4gICAgZWxzZSBwYmYud3JpdGVNZXNzYWdlKDYsIHdyaXRlR2VvbWV0cnksIG9iaik7XG5cbiAgICBrZXlzID0gbnVsbDtcblxuICAgIHJldHVybiBwYmYuZmluaXNoKCk7XG59XG5cbmZ1bmN0aW9uIGFuYWx5emUob2JqKSB7XG4gICAgdmFyIGksIGtleTtcblxuICAgIGlmIChvYmoudHlwZSA9PT0gJ0ZlYXR1cmVDb2xsZWN0aW9uJykge1xuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgb2JqLmZlYXR1cmVzLmxlbmd0aDsgaSsrKSBhbmFseXplKG9iai5mZWF0dXJlc1tpXSk7XG5cbiAgICB9IGVsc2UgaWYgKG9iai50eXBlID09PSAnRmVhdHVyZScpIHtcbiAgICAgICAgaWYgKG9iai5nZW9tZXRyeSAhPT0gbnVsbCkgYW5hbHl6ZShvYmouZ2VvbWV0cnkpO1xuICAgICAgICBmb3IgKGtleSBpbiBvYmoucHJvcGVydGllcykgc2F2ZUtleShrZXkpO1xuXG4gICAgfSBlbHNlIGlmIChvYmoudHlwZSA9PT0gJ1BvaW50JykgYW5hbHl6ZVBvaW50KG9iai5jb29yZGluYXRlcyk7XG4gICAgZWxzZSBpZiAob2JqLnR5cGUgPT09ICdNdWx0aVBvaW50JykgYW5hbHl6ZVBvaW50cyhvYmouY29vcmRpbmF0ZXMpO1xuICAgIGVsc2UgaWYgKG9iai50eXBlID09PSAnR2VvbWV0cnlDb2xsZWN0aW9uJykge1xuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgb2JqLmdlb21ldHJpZXMubGVuZ3RoOyBpKyspIGFuYWx5emUob2JqLmdlb21ldHJpZXNbaV0pO1xuICAgIH1cbiAgICBlbHNlIGlmIChvYmoudHlwZSA9PT0gJ0xpbmVTdHJpbmcnKSBhbmFseXplUG9pbnRzKG9iai5jb29yZGluYXRlcyk7XG4gICAgZWxzZSBpZiAob2JqLnR5cGUgPT09ICdQb2x5Z29uJyB8fCBvYmoudHlwZSA9PT0gJ011bHRpTGluZVN0cmluZycpIGFuYWx5emVNdWx0aUxpbmUob2JqLmNvb3JkaW5hdGVzKTtcbiAgICBlbHNlIGlmIChvYmoudHlwZSA9PT0gJ011bHRpUG9seWdvbicpIHtcbiAgICAgICAgZm9yIChpID0gMDsgaSA8IG9iai5jb29yZGluYXRlcy5sZW5ndGg7IGkrKykgYW5hbHl6ZU11bHRpTGluZShvYmouY29vcmRpbmF0ZXNbaV0pO1xuICAgIH1cblxuICAgIGZvciAoa2V5IGluIG9iaikge1xuICAgICAgICBpZiAoIWlzU3BlY2lhbEtleShrZXksIG9iai50eXBlKSkgc2F2ZUtleShrZXkpO1xuICAgIH1cbn1cblxuZnVuY3Rpb24gYW5hbHl6ZU11bHRpTGluZShjb29yZHMpIHtcbiAgICBmb3IgKHZhciBpID0gMDsgaSA8IGNvb3Jkcy5sZW5ndGg7IGkrKykgYW5hbHl6ZVBvaW50cyhjb29yZHNbaV0pO1xufVxuXG5mdW5jdGlvbiBhbmFseXplUG9pbnRzKGNvb3Jkcykge1xuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgY29vcmRzLmxlbmd0aDsgaSsrKSBhbmFseXplUG9pbnQoY29vcmRzW2ldKTtcbn1cblxuZnVuY3Rpb24gYW5hbHl6ZVBvaW50KHBvaW50KSB7XG4gICAgZGltID0gTWF0aC5tYXgoZGltLCBwb2ludC5sZW5ndGgpO1xuXG4gICAgLy8gZmluZCBtYXggcHJlY2lzaW9uXG4gICAgZm9yICh2YXIgaSA9IDA7IGkgPCBwb2ludC5sZW5ndGg7IGkrKykge1xuICAgICAgICB3aGlsZSAoTWF0aC5yb3VuZChwb2ludFtpXSAqIGUpIC8gZSAhPT0gcG9pbnRbaV0gJiYgZSA8IG1heFByZWNpc2lvbikgZSAqPSAxMDtcbiAgICB9XG59XG5cbmZ1bmN0aW9uIHNhdmVLZXkoa2V5KSB7XG4gICAgaWYgKGtleXNba2V5XSA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGtleXNBcnIucHVzaChrZXkpO1xuICAgICAgICBrZXlzW2tleV0gPSBrZXlzTnVtKys7XG4gICAgfVxufVxuXG5mdW5jdGlvbiB3cml0ZUZlYXR1cmVDb2xsZWN0aW9uKG9iaiwgcGJmKSB7XG4gICAgZm9yICh2YXIgaSA9IDA7IGkgPCBvYmouZmVhdHVyZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgcGJmLndyaXRlTWVzc2FnZSgxLCB3cml0ZUZlYXR1cmUsIG9iai5mZWF0dXJlc1tpXSk7XG4gICAgfVxuICAgIHdyaXRlUHJvcHMob2JqLCBwYmYsIHRydWUpO1xufVxuXG5mdW5jdGlvbiB3cml0ZUZlYXR1cmUoZmVhdHVyZSwgcGJmKSB7XG4gICAgaWYgKGZlYXR1cmUuZ2VvbWV0cnkgIT09IG51bGwpIHBiZi53cml0ZU1lc3NhZ2UoMSwgd3JpdGVHZW9tZXRyeSwgZmVhdHVyZS5nZW9tZXRyeSk7XG5cbiAgICBpZiAoZmVhdHVyZS5pZCAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGlmICh0eXBlb2YgZmVhdHVyZS5pZCA9PT0gJ251bWJlcicgJiYgZmVhdHVyZS5pZCAlIDEgPT09IDApIHBiZi53cml0ZVNWYXJpbnRGaWVsZCgxMiwgZmVhdHVyZS5pZCk7XG4gICAgICAgIGVsc2UgcGJmLndyaXRlU3RyaW5nRmllbGQoMTEsIGZlYXR1cmUuaWQpO1xuICAgIH1cblxuICAgIGlmIChmZWF0dXJlLnByb3BlcnRpZXMpIHdyaXRlUHJvcHMoZmVhdHVyZS5wcm9wZXJ0aWVzLCBwYmYpO1xuICAgIHdyaXRlUHJvcHMoZmVhdHVyZSwgcGJmLCB0cnVlKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVHZW9tZXRyeShnZW9tLCBwYmYpIHtcbiAgICBwYmYud3JpdGVWYXJpbnRGaWVsZCgxLCBnZW9tZXRyeVR5cGVzW2dlb20udHlwZV0pO1xuXG4gICAgdmFyIGNvb3JkcyA9IGdlb20uY29vcmRpbmF0ZXM7XG5cbiAgICBpZiAoZ2VvbS50eXBlID09PSAnUG9pbnQnKSB3cml0ZVBvaW50KGNvb3JkcywgcGJmKTtcbiAgICBlbHNlIGlmIChnZW9tLnR5cGUgPT09ICdNdWx0aVBvaW50Jykgd3JpdGVMaW5lKGNvb3JkcywgcGJmLCB0cnVlKTtcbiAgICBlbHNlIGlmIChnZW9tLnR5cGUgPT09ICdMaW5lU3RyaW5nJykgd3JpdGVMaW5lKGNvb3JkcywgcGJmKTtcbiAgICBlbHNlIGlmIChnZW9tLnR5cGUgPT09ICdNdWx0aUxpbmVTdHJpbmcnKSB3cml0ZU11bHRpTGluZShjb29yZHMsIHBiZik7XG4gICAgZWxzZSBpZiAoZ2VvbS50eXBlID09PSAnUG9seWdvbicpIHdyaXRlTXVsdGlMaW5lKGNvb3JkcywgcGJmLCB0cnVlKTtcbiAgICBlbHNlIGlmIChnZW9tLnR5cGUgPT09ICdNdWx0aVBvbHlnb24nKSB3cml0ZU11bHRpUG9seWdvbihjb29yZHMsIHBiZik7XG4gICAgZWxzZSBpZiAoZ2VvbS50eXBlID09PSAnR2VvbWV0cnlDb2xsZWN0aW9uJykge1xuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGdlb20uZ2VvbWV0cmllcy5sZW5ndGg7IGkrKykgcGJmLndyaXRlTWVzc2FnZSg0LCB3cml0ZUdlb21ldHJ5LCBnZW9tLmdlb21ldHJpZXNbaV0pO1xuICAgIH1cblxuICAgIHdyaXRlUHJvcHMoZ2VvbSwgcGJmLCB0cnVlKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVQcm9wcyhwcm9wcywgcGJmLCBpc0N1c3RvbSkge1xuICAgIHZhciBpbmRleGVzID0gW10sXG4gICAgICAgIHZhbHVlSW5kZXggPSAwO1xuXG4gICAgZm9yICh2YXIga2V5IGluIHByb3BzKSB7XG4gICAgICAgIGlmIChpc0N1c3RvbSAmJiBpc1NwZWNpYWxLZXkoa2V5LCBwcm9wcy50eXBlKSkge1xuICAgICAgICAgICAgY29udGludWU7XG4gICAgICAgIH1cbiAgICAgICAgcGJmLndyaXRlTWVzc2FnZSgxMywgd3JpdGVWYWx1ZSwgcHJvcHNba2V5XSk7XG4gICAgICAgIGluZGV4ZXMucHVzaChrZXlzW2tleV0pO1xuICAgICAgICBpbmRleGVzLnB1c2godmFsdWVJbmRleCsrKTtcbiAgICB9XG4gICAgcGJmLndyaXRlUGFja2VkVmFyaW50KGlzQ3VzdG9tID8gMTUgOiAxNCwgaW5kZXhlcyk7XG59XG5cbmZ1bmN0aW9uIHdyaXRlVmFsdWUodmFsdWUsIHBiZikge1xuICAgIGlmICh2YWx1ZSA9PT0gbnVsbCkgcmV0dXJuO1xuXG4gICAgdmFyIHR5cGUgPSB0eXBlb2YgdmFsdWU7XG5cbiAgICBpZiAodHlwZSA9PT0gJ3N0cmluZycpIHBiZi53cml0ZVN0cmluZ0ZpZWxkKDEsIHZhbHVlKTtcbiAgICBlbHNlIGlmICh0eXBlID09PSAnYm9vbGVhbicpIHBiZi53cml0ZUJvb2xlYW5GaWVsZCg1LCB2YWx1ZSk7XG4gICAgZWxzZSBpZiAodHlwZSA9PT0gJ29iamVjdCcpIHBiZi53cml0ZVN0cmluZ0ZpZWxkKDYsIEpTT04uc3RyaW5naWZ5KHZhbHVlKSk7XG4gICAgZWxzZSBpZiAodHlwZSA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgaWYgKHZhbHVlICUgMSAhPT0gMCkgcGJmLndyaXRlRG91YmxlRmllbGQoMiwgdmFsdWUpO1xuICAgICAgICBlbHNlIGlmICh2YWx1ZSA+PSAwKSBwYmYud3JpdGVWYXJpbnRGaWVsZCgzLCB2YWx1ZSk7XG4gICAgICAgIGVsc2UgcGJmLndyaXRlVmFyaW50RmllbGQoNCwgLXZhbHVlKTtcbiAgICB9XG59XG5cbmZ1bmN0aW9uIHdyaXRlUG9pbnQocG9pbnQsIHBiZikge1xuICAgIHZhciBjb29yZHMgPSBbXTtcbiAgICBmb3IgKHZhciBpID0gMDsgaSA8IGRpbTsgaSsrKSBjb29yZHMucHVzaChNYXRoLnJvdW5kKHBvaW50W2ldICogZSkpO1xuICAgIHBiZi53cml0ZVBhY2tlZFNWYXJpbnQoMywgY29vcmRzKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVMaW5lKGxpbmUsIHBiZikge1xuICAgIHZhciBjb29yZHMgPSBbXTtcbiAgICBwb3B1bGF0ZUxpbmUoY29vcmRzLCBsaW5lKTtcbiAgICBwYmYud3JpdGVQYWNrZWRTVmFyaW50KDMsIGNvb3Jkcyk7XG59XG5cbmZ1bmN0aW9uIHdyaXRlTXVsdGlMaW5lKGxpbmVzLCBwYmYsIGNsb3NlZCkge1xuICAgIHZhciBsZW4gPSBsaW5lcy5sZW5ndGgsXG4gICAgICAgIGk7XG4gICAgaWYgKGxlbiAhPT0gMSkge1xuICAgICAgICB2YXIgbGVuZ3RocyA9IFtdO1xuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgbGVuOyBpKyspIGxlbmd0aHMucHVzaChsaW5lc1tpXS5sZW5ndGggLSAoY2xvc2VkID8gMSA6IDApKTtcbiAgICAgICAgcGJmLndyaXRlUGFja2VkVmFyaW50KDIsIGxlbmd0aHMpO1xuICAgICAgICAvLyBUT0RPIGZhc3RlciB3aXRoIGN1c3RvbSB3cml0ZU1lc3NhZ2U/XG4gICAgfVxuICAgIHZhciBjb29yZHMgPSBbXTtcbiAgICBmb3IgKGkgPSAwOyBpIDwgbGVuOyBpKyspIHBvcHVsYXRlTGluZShjb29yZHMsIGxpbmVzW2ldLCBjbG9zZWQpO1xuICAgIHBiZi53cml0ZVBhY2tlZFNWYXJpbnQoMywgY29vcmRzKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVNdWx0aVBvbHlnb24ocG9seWdvbnMsIHBiZikge1xuICAgIHZhciBsZW4gPSBwb2x5Z29ucy5sZW5ndGgsXG4gICAgICAgIGksIGo7XG4gICAgaWYgKGxlbiAhPT0gMSB8fCBwb2x5Z29uc1swXS5sZW5ndGggIT09IDEpIHtcbiAgICAgICAgdmFyIGxlbmd0aHMgPSBbbGVuXTtcbiAgICAgICAgZm9yIChpID0gMDsgaSA8IGxlbjsgaSsrKSB7XG4gICAgICAgICAgICBsZW5ndGhzLnB1c2gocG9seWdvbnNbaV0ubGVuZ3RoKTtcbiAgICAgICAgICAgIGZvciAoaiA9IDA7IGogPCBwb2x5Z29uc1tpXS5sZW5ndGg7IGorKykgbGVuZ3Rocy5wdXNoKHBvbHlnb25zW2ldW2pdLmxlbmd0aCAtIDEpO1xuICAgICAgICB9XG4gICAgICAgIHBiZi53cml0ZVBhY2tlZFZhcmludCgyLCBsZW5ndGhzKTtcbiAgICB9XG5cbiAgICB2YXIgY29vcmRzID0gW107XG4gICAgZm9yIChpID0gMDsgaSA8IGxlbjsgaSsrKSB7XG4gICAgICAgIGZvciAoaiA9IDA7IGogPCBwb2x5Z29uc1tpXS5sZW5ndGg7IGorKykgcG9wdWxhdGVMaW5lKGNvb3JkcywgcG9seWdvbnNbaV1bal0sIHRydWUpO1xuICAgIH1cbiAgICBwYmYud3JpdGVQYWNrZWRTVmFyaW50KDMsIGNvb3Jkcyk7XG59XG5cbmZ1bmN0aW9uIHBvcHVsYXRlTGluZShjb29yZHMsIGxpbmUsIGNsb3NlZCkge1xuICAgIHZhciBpLCBqLFxuICAgICAgICBsZW4gPSBsaW5lLmxlbmd0aCAtIChjbG9zZWQgPyAxIDogMCksXG4gICAgICAgIHN1bSA9IG5ldyBBcnJheShkaW0pO1xuICAgIGZvciAoaiA9IDA7IGogPCBkaW07IGorKykgc3VtW2pdID0gMDtcbiAgICBmb3IgKGkgPSAwOyBpIDwgbGVuOyBpKyspIHtcbiAgICAgICAgZm9yIChqID0gMDsgaiA8IGRpbTsgaisrKSB7XG4gICAgICAgICAgICB2YXIgbiA9IE1hdGgucm91bmQobGluZVtpXVtqXSAqIGUpIC0gc3VtW2pdO1xuICAgICAgICAgICAgY29vcmRzLnB1c2gobik7XG4gICAgICAgICAgICBzdW1bal0gKz0gbjtcbiAgICAgICAgfVxuICAgIH1cbn1cblxuZnVuY3Rpb24gaXNTcGVjaWFsS2V5KGtleSwgdHlwZSkge1xuICAgIGlmIChrZXkgPT09ICd0eXBlJykgcmV0dXJuIHRydWU7XG4gICAgZWxzZSBpZiAodHlwZSA9PT0gJ0ZlYXR1cmVDb2xsZWN0aW9uJykge1xuICAgICAgICBpZiAoa2V5ID09PSAnZmVhdHVyZXMnKSByZXR1cm4gdHJ1ZTtcbiAgICB9IGVsc2UgaWYgKHR5cGUgPT09ICdGZWF0dXJlJykge1xuICAgICAgICBpZiAoa2V5ID09PSAnaWQnIHx8IGtleSA9PT0gJ3Byb3BlcnRpZXMnIHx8IGtleSA9PT0gJ2dlb21ldHJ5JykgcmV0dXJuIHRydWU7XG4gICAgfSBlbHNlIGlmICh0eXBlID09PSAnR2VvbWV0cnlDb2xsZWN0aW9uJykge1xuICAgICAgICBpZiAoa2V5ID09PSAnZ2VvbWV0cmllcycpIHJldHVybiB0cnVlO1xuICAgIH0gZWxzZSBpZiAoa2V5ID09PSAnY29vcmRpbmF0ZXMnKSByZXR1cm4gdHJ1ZTtcbiAgICByZXR1cm4gZmFsc2U7XG59XG4iLCIndXNlIHN0cmljdCc7XG5cbmV4cG9ydHMuZW5jb2RlID0gcmVxdWlyZSgnLi9lbmNvZGUnKTtcbmV4cG9ydHMuZGVjb2RlID0gcmVxdWlyZSgnLi9kZWNvZGUnKTtcbiIsIi8qISBpZWVlNzU0LiBCU0QtMy1DbGF1c2UgTGljZW5zZS4gRmVyb3NzIEFib3VraGFkaWplaCA8aHR0cHM6Ly9mZXJvc3Mub3JnL29wZW5zb3VyY2U+ICovXG5leHBvcnRzLnJlYWQgPSBmdW5jdGlvbiAoYnVmZmVyLCBvZmZzZXQsIGlzTEUsIG1MZW4sIG5CeXRlcykge1xuICB2YXIgZSwgbVxuICB2YXIgZUxlbiA9IChuQnl0ZXMgKiA4KSAtIG1MZW4gLSAxXG4gIHZhciBlTWF4ID0gKDEgPDwgZUxlbikgLSAxXG4gIHZhciBlQmlhcyA9IGVNYXggPj4gMVxuICB2YXIgbkJpdHMgPSAtN1xuICB2YXIgaSA9IGlzTEUgPyAobkJ5dGVzIC0gMSkgOiAwXG4gIHZhciBkID0gaXNMRSA/IC0xIDogMVxuICB2YXIgcyA9IGJ1ZmZlcltvZmZzZXQgKyBpXVxuXG4gIGkgKz0gZFxuXG4gIGUgPSBzICYgKCgxIDw8ICgtbkJpdHMpKSAtIDEpXG4gIHMgPj49ICgtbkJpdHMpXG4gIG5CaXRzICs9IGVMZW5cbiAgZm9yICg7IG5CaXRzID4gMDsgZSA9IChlICogMjU2KSArIGJ1ZmZlcltvZmZzZXQgKyBpXSwgaSArPSBkLCBuQml0cyAtPSA4KSB7fVxuXG4gIG0gPSBlICYgKCgxIDw8ICgtbkJpdHMpKSAtIDEpXG4gIGUgPj49ICgtbkJpdHMpXG4gIG5CaXRzICs9IG1MZW5cbiAgZm9yICg7IG5CaXRzID4gMDsgbSA9IChtICogMjU2KSArIGJ1ZmZlcltvZmZzZXQgKyBpXSwgaSArPSBkLCBuQml0cyAtPSA4KSB7fVxuXG4gIGlmIChlID09PSAwKSB7XG4gICAgZSA9IDEgLSBlQmlhc1xuICB9IGVsc2UgaWYgKGUgPT09IGVNYXgpIHtcbiAgICByZXR1cm4gbSA/IE5hTiA6ICgocyA/IC0xIDogMSkgKiBJbmZpbml0eSlcbiAgfSBlbHNlIHtcbiAgICBtID0gbSArIE1hdGgucG93KDIsIG1MZW4pXG4gICAgZSA9IGUgLSBlQmlhc1xuICB9XG4gIHJldHVybiAocyA/IC0xIDogMSkgKiBtICogTWF0aC5wb3coMiwgZSAtIG1MZW4pXG59XG5cbmV4cG9ydHMud3JpdGUgPSBmdW5jdGlvbiAoYnVmZmVyLCB2YWx1ZSwgb2Zmc2V0LCBpc0xFLCBtTGVuLCBuQnl0ZXMpIHtcbiAgdmFyIGUsIG0sIGNcbiAgdmFyIGVMZW4gPSAobkJ5dGVzICogOCkgLSBtTGVuIC0gMVxuICB2YXIgZU1heCA9ICgxIDw8IGVMZW4pIC0gMVxuICB2YXIgZUJpYXMgPSBlTWF4ID4+IDFcbiAgdmFyIHJ0ID0gKG1MZW4gPT09IDIzID8gTWF0aC5wb3coMiwgLTI0KSAtIE1hdGgucG93KDIsIC03NykgOiAwKVxuICB2YXIgaSA9IGlzTEUgPyAwIDogKG5CeXRlcyAtIDEpXG4gIHZhciBkID0gaXNMRSA/IDEgOiAtMVxuICB2YXIgcyA9IHZhbHVlIDwgMCB8fCAodmFsdWUgPT09IDAgJiYgMSAvIHZhbHVlIDwgMCkgPyAxIDogMFxuXG4gIHZhbHVlID0gTWF0aC5hYnModmFsdWUpXG5cbiAgaWYgKGlzTmFOKHZhbHVlKSB8fCB2YWx1ZSA9PT0gSW5maW5pdHkpIHtcbiAgICBtID0gaXNOYU4odmFsdWUpID8gMSA6IDBcbiAgICBlID0gZU1heFxuICB9IGVsc2Uge1xuICAgIGUgPSBNYXRoLmZsb29yKE1hdGgubG9nKHZhbHVlKSAvIE1hdGguTE4yKVxuICAgIGlmICh2YWx1ZSAqIChjID0gTWF0aC5wb3coMiwgLWUpKSA8IDEpIHtcbiAgICAgIGUtLVxuICAgICAgYyAqPSAyXG4gICAgfVxuICAgIGlmIChlICsgZUJpYXMgPj0gMSkge1xuICAgICAgdmFsdWUgKz0gcnQgLyBjXG4gICAgfSBlbHNlIHtcbiAgICAgIHZhbHVlICs9IHJ0ICogTWF0aC5wb3coMiwgMSAtIGVCaWFzKVxuICAgIH1cbiAgICBpZiAodmFsdWUgKiBjID49IDIpIHtcbiAgICAgIGUrK1xuICAgICAgYyAvPSAyXG4gICAgfVxuXG4gICAgaWYgKGUgKyBlQmlhcyA+PSBlTWF4KSB7XG4gICAgICBtID0gMFxuICAgICAgZSA9IGVNYXhcbiAgICB9IGVsc2UgaWYgKGUgKyBlQmlhcyA+PSAxKSB7XG4gICAgICBtID0gKCh2YWx1ZSAqIGMpIC0gMSkgKiBNYXRoLnBvdygyLCBtTGVuKVxuICAgICAgZSA9IGUgKyBlQmlhc1xuICAgIH0gZWxzZSB7XG4gICAgICBtID0gdmFsdWUgKiBNYXRoLnBvdygyLCBlQmlhcyAtIDEpICogTWF0aC5wb3coMiwgbUxlbilcbiAgICAgIGUgPSAwXG4gICAgfVxuICB9XG5cbiAgZm9yICg7IG1MZW4gPj0gODsgYnVmZmVyW29mZnNldCArIGldID0gbSAmIDB4ZmYsIGkgKz0gZCwgbSAvPSAyNTYsIG1MZW4gLT0gOCkge31cblxuICBlID0gKGUgPDwgbUxlbikgfCBtXG4gIGVMZW4gKz0gbUxlblxuICBmb3IgKDsgZUxlbiA+IDA7IGJ1ZmZlcltvZmZzZXQgKyBpXSA9IGUgJiAweGZmLCBpICs9IGQsIGUgLz0gMjU2LCBlTGVuIC09IDgpIHt9XG5cbiAgYnVmZmVyW29mZnNldCArIGkgLSBkXSB8PSBzICogMTI4XG59XG4iLCIndXNlIHN0cmljdCc7XG5cbm1vZHVsZS5leHBvcnRzID0gUGJmO1xuXG52YXIgaWVlZTc1NCA9IHJlcXVpcmUoJ2llZWU3NTQnKTtcblxuZnVuY3Rpb24gUGJmKGJ1Zikge1xuICAgIHRoaXMuYnVmID0gQXJyYXlCdWZmZXIuaXNWaWV3ICYmIEFycmF5QnVmZmVyLmlzVmlldyhidWYpID8gYnVmIDogbmV3IFVpbnQ4QXJyYXkoYnVmIHx8IDApO1xuICAgIHRoaXMucG9zID0gMDtcbiAgICB0aGlzLnR5cGUgPSAwO1xuICAgIHRoaXMubGVuZ3RoID0gdGhpcy5idWYubGVuZ3RoO1xufVxuXG5QYmYuVmFyaW50ICA9IDA7IC8vIHZhcmludDogaW50MzIsIGludDY0LCB1aW50MzIsIHVpbnQ2NCwgc2ludDMyLCBzaW50NjQsIGJvb2wsIGVudW1cblBiZi5GaXhlZDY0ID0gMTsgLy8gNjQtYml0OiBkb3VibGUsIGZpeGVkNjQsIHNmaXhlZDY0XG5QYmYuQnl0ZXMgICA9IDI7IC8vIGxlbmd0aC1kZWxpbWl0ZWQ6IHN0cmluZywgYnl0ZXMsIGVtYmVkZGVkIG1lc3NhZ2VzLCBwYWNrZWQgcmVwZWF0ZWQgZmllbGRzXG5QYmYuRml4ZWQzMiA9IDU7IC8vIDMyLWJpdDogZmxvYXQsIGZpeGVkMzIsIHNmaXhlZDMyXG5cbnZhciBTSElGVF9MRUZUXzMyID0gKDEgPDwgMTYpICogKDEgPDwgMTYpLFxuICAgIFNISUZUX1JJR0hUXzMyID0gMSAvIFNISUZUX0xFRlRfMzI7XG5cbi8vIFRocmVzaG9sZCBjaG9zZW4gYmFzZWQgb24gYm90aCBiZW5jaG1hcmtpbmcgYW5kIGtub3dsZWRnZSBhYm91dCBicm93c2VyIHN0cmluZ1xuLy8gZGF0YSBzdHJ1Y3R1cmVzICh3aGljaCBjdXJyZW50bHkgc3dpdGNoIHN0cnVjdHVyZSB0eXBlcyBhdCAxMiBieXRlcyBvciBtb3JlKVxudmFyIFRFWFRfREVDT0RFUl9NSU5fTEVOR1RIID0gMTI7XG52YXIgdXRmOFRleHREZWNvZGVyID0gdHlwZW9mIFRleHREZWNvZGVyID09PSAndW5kZWZpbmVkJyA/IG51bGwgOiBuZXcgVGV4dERlY29kZXIoJ3V0ZjgnKTtcblxuUGJmLnByb3RvdHlwZSA9IHtcblxuICAgIGRlc3Ryb3k6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmJ1ZiA9IG51bGw7XG4gICAgfSxcblxuICAgIC8vID09PSBSRUFESU5HID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG5cbiAgICByZWFkRmllbGRzOiBmdW5jdGlvbihyZWFkRmllbGQsIHJlc3VsdCwgZW5kKSB7XG4gICAgICAgIGVuZCA9IGVuZCB8fCB0aGlzLmxlbmd0aDtcblxuICAgICAgICB3aGlsZSAodGhpcy5wb3MgPCBlbmQpIHtcbiAgICAgICAgICAgIHZhciB2YWwgPSB0aGlzLnJlYWRWYXJpbnQoKSxcbiAgICAgICAgICAgICAgICB0YWcgPSB2YWwgPj4gMyxcbiAgICAgICAgICAgICAgICBzdGFydFBvcyA9IHRoaXMucG9zO1xuXG4gICAgICAgICAgICB0aGlzLnR5cGUgPSB2YWwgJiAweDc7XG4gICAgICAgICAgICByZWFkRmllbGQodGFnLCByZXN1bHQsIHRoaXMpO1xuXG4gICAgICAgICAgICBpZiAodGhpcy5wb3MgPT09IHN0YXJ0UG9zKSB0aGlzLnNraXAodmFsKTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcmVzdWx0O1xuICAgIH0sXG5cbiAgICByZWFkTWVzc2FnZTogZnVuY3Rpb24ocmVhZEZpZWxkLCByZXN1bHQpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMucmVhZEZpZWxkcyhyZWFkRmllbGQsIHJlc3VsdCwgdGhpcy5yZWFkVmFyaW50KCkgKyB0aGlzLnBvcyk7XG4gICAgfSxcblxuICAgIHJlYWRGaXhlZDMyOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIHZhbCA9IHJlYWRVSW50MzIodGhpcy5idWYsIHRoaXMucG9zKTtcbiAgICAgICAgdGhpcy5wb3MgKz0gNDtcbiAgICAgICAgcmV0dXJuIHZhbDtcbiAgICB9LFxuXG4gICAgcmVhZFNGaXhlZDMyOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIHZhbCA9IHJlYWRJbnQzMih0aGlzLmJ1ZiwgdGhpcy5wb3MpO1xuICAgICAgICB0aGlzLnBvcyArPSA0O1xuICAgICAgICByZXR1cm4gdmFsO1xuICAgIH0sXG5cbiAgICAvLyA2NC1iaXQgaW50IGhhbmRsaW5nIGlzIGJhc2VkIG9uIGdpdGh1Yi5jb20vZHB3L25vZGUtYnVmZmVyLW1vcmUtaW50cyAoTUlULWxpY2Vuc2VkKVxuXG4gICAgcmVhZEZpeGVkNjQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgdmFsID0gcmVhZFVJbnQzMih0aGlzLmJ1ZiwgdGhpcy5wb3MpICsgcmVhZFVJbnQzMih0aGlzLmJ1ZiwgdGhpcy5wb3MgKyA0KSAqIFNISUZUX0xFRlRfMzI7XG4gICAgICAgIHRoaXMucG9zICs9IDg7XG4gICAgICAgIHJldHVybiB2YWw7XG4gICAgfSxcblxuICAgIHJlYWRTRml4ZWQ2NDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciB2YWwgPSByZWFkVUludDMyKHRoaXMuYnVmLCB0aGlzLnBvcykgKyByZWFkSW50MzIodGhpcy5idWYsIHRoaXMucG9zICsgNCkgKiBTSElGVF9MRUZUXzMyO1xuICAgICAgICB0aGlzLnBvcyArPSA4O1xuICAgICAgICByZXR1cm4gdmFsO1xuICAgIH0sXG5cbiAgICByZWFkRmxvYXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgdmFsID0gaWVlZTc1NC5yZWFkKHRoaXMuYnVmLCB0aGlzLnBvcywgdHJ1ZSwgMjMsIDQpO1xuICAgICAgICB0aGlzLnBvcyArPSA0O1xuICAgICAgICByZXR1cm4gdmFsO1xuICAgIH0sXG5cbiAgICByZWFkRG91YmxlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIHZhbCA9IGllZWU3NTQucmVhZCh0aGlzLmJ1ZiwgdGhpcy5wb3MsIHRydWUsIDUyLCA4KTtcbiAgICAgICAgdGhpcy5wb3MgKz0gODtcbiAgICAgICAgcmV0dXJuIHZhbDtcbiAgICB9LFxuXG4gICAgcmVhZFZhcmludDogZnVuY3Rpb24oaXNTaWduZWQpIHtcbiAgICAgICAgdmFyIGJ1ZiA9IHRoaXMuYnVmLFxuICAgICAgICAgICAgdmFsLCBiO1xuXG4gICAgICAgIGIgPSBidWZbdGhpcy5wb3MrK107IHZhbCAgPSAgYiAmIDB4N2Y7ICAgICAgICBpZiAoYiA8IDB4ODApIHJldHVybiB2YWw7XG4gICAgICAgIGIgPSBidWZbdGhpcy5wb3MrK107IHZhbCB8PSAoYiAmIDB4N2YpIDw8IDc7ICBpZiAoYiA8IDB4ODApIHJldHVybiB2YWw7XG4gICAgICAgIGIgPSBidWZbdGhpcy5wb3MrK107IHZhbCB8PSAoYiAmIDB4N2YpIDw8IDE0OyBpZiAoYiA8IDB4ODApIHJldHVybiB2YWw7XG4gICAgICAgIGIgPSBidWZbdGhpcy5wb3MrK107IHZhbCB8PSAoYiAmIDB4N2YpIDw8IDIxOyBpZiAoYiA8IDB4ODApIHJldHVybiB2YWw7XG4gICAgICAgIGIgPSBidWZbdGhpcy5wb3NdOyAgIHZhbCB8PSAoYiAmIDB4MGYpIDw8IDI4O1xuXG4gICAgICAgIHJldHVybiByZWFkVmFyaW50UmVtYWluZGVyKHZhbCwgaXNTaWduZWQsIHRoaXMpO1xuICAgIH0sXG5cbiAgICByZWFkVmFyaW50NjQ6IGZ1bmN0aW9uKCkgeyAvLyBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIHYyLjAuMVxuICAgICAgICByZXR1cm4gdGhpcy5yZWFkVmFyaW50KHRydWUpO1xuICAgIH0sXG5cbiAgICByZWFkU1ZhcmludDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBudW0gPSB0aGlzLnJlYWRWYXJpbnQoKTtcbiAgICAgICAgcmV0dXJuIG51bSAlIDIgPT09IDEgPyAobnVtICsgMSkgLyAtMiA6IG51bSAvIDI7IC8vIHppZ3phZyBlbmNvZGluZ1xuICAgIH0sXG5cbiAgICByZWFkQm9vbGVhbjogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiBCb29sZWFuKHRoaXMucmVhZFZhcmludCgpKTtcbiAgICB9LFxuXG4gICAgcmVhZFN0cmluZzogZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBlbmQgPSB0aGlzLnJlYWRWYXJpbnQoKSArIHRoaXMucG9zO1xuICAgICAgICB2YXIgcG9zID0gdGhpcy5wb3M7XG4gICAgICAgIHRoaXMucG9zID0gZW5kO1xuXG4gICAgICAgIGlmIChlbmQgLSBwb3MgPj0gVEVYVF9ERUNPREVSX01JTl9MRU5HVEggJiYgdXRmOFRleHREZWNvZGVyKSB7XG4gICAgICAgICAgICAvLyBsb25nZXIgc3RyaW5ncyBhcmUgZmFzdCB3aXRoIHRoZSBidWlsdC1pbiBicm93c2VyIFRleHREZWNvZGVyIEFQSVxuICAgICAgICAgICAgcmV0dXJuIHJlYWRVdGY4VGV4dERlY29kZXIodGhpcy5idWYsIHBvcywgZW5kKTtcbiAgICAgICAgfVxuICAgICAgICAvLyBzaG9ydCBzdHJpbmdzIGFyZSBmYXN0IHdpdGggb3VyIGN1c3RvbSBpbXBsZW1lbnRhdGlvblxuICAgICAgICByZXR1cm4gcmVhZFV0ZjgodGhpcy5idWYsIHBvcywgZW5kKTtcbiAgICB9LFxuXG4gICAgcmVhZEJ5dGVzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIGVuZCA9IHRoaXMucmVhZFZhcmludCgpICsgdGhpcy5wb3MsXG4gICAgICAgICAgICBidWZmZXIgPSB0aGlzLmJ1Zi5zdWJhcnJheSh0aGlzLnBvcywgZW5kKTtcbiAgICAgICAgdGhpcy5wb3MgPSBlbmQ7XG4gICAgICAgIHJldHVybiBidWZmZXI7XG4gICAgfSxcblxuICAgIC8vIHZlcmJvc2UgZm9yIHBlcmZvcm1hbmNlIHJlYXNvbnM7IGRvZXNuJ3QgYWZmZWN0IGd6aXBwZWQgc2l6ZVxuXG4gICAgcmVhZFBhY2tlZFZhcmludDogZnVuY3Rpb24oYXJyLCBpc1NpZ25lZCkge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWRWYXJpbnQoaXNTaWduZWQpKTtcbiAgICAgICAgdmFyIGVuZCA9IHJlYWRQYWNrZWRFbmQodGhpcyk7XG4gICAgICAgIGFyciA9IGFyciB8fCBbXTtcbiAgICAgICAgd2hpbGUgKHRoaXMucG9zIDwgZW5kKSBhcnIucHVzaCh0aGlzLnJlYWRWYXJpbnQoaXNTaWduZWQpKTtcbiAgICAgICAgcmV0dXJuIGFycjtcbiAgICB9LFxuICAgIHJlYWRQYWNrZWRTVmFyaW50OiBmdW5jdGlvbihhcnIpIHtcbiAgICAgICAgaWYgKHRoaXMudHlwZSAhPT0gUGJmLkJ5dGVzKSByZXR1cm4gYXJyLnB1c2godGhpcy5yZWFkU1ZhcmludCgpKTtcbiAgICAgICAgdmFyIGVuZCA9IHJlYWRQYWNrZWRFbmQodGhpcyk7XG4gICAgICAgIGFyciA9IGFyciB8fCBbXTtcbiAgICAgICAgd2hpbGUgKHRoaXMucG9zIDwgZW5kKSBhcnIucHVzaCh0aGlzLnJlYWRTVmFyaW50KCkpO1xuICAgICAgICByZXR1cm4gYXJyO1xuICAgIH0sXG4gICAgcmVhZFBhY2tlZEJvb2xlYW46IGZ1bmN0aW9uKGFycikge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWRCb29sZWFuKCkpO1xuICAgICAgICB2YXIgZW5kID0gcmVhZFBhY2tlZEVuZCh0aGlzKTtcbiAgICAgICAgYXJyID0gYXJyIHx8IFtdO1xuICAgICAgICB3aGlsZSAodGhpcy5wb3MgPCBlbmQpIGFyci5wdXNoKHRoaXMucmVhZEJvb2xlYW4oKSk7XG4gICAgICAgIHJldHVybiBhcnI7XG4gICAgfSxcbiAgICByZWFkUGFja2VkRmxvYXQ6IGZ1bmN0aW9uKGFycikge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWRGbG9hdCgpKTtcbiAgICAgICAgdmFyIGVuZCA9IHJlYWRQYWNrZWRFbmQodGhpcyk7XG4gICAgICAgIGFyciA9IGFyciB8fCBbXTtcbiAgICAgICAgd2hpbGUgKHRoaXMucG9zIDwgZW5kKSBhcnIucHVzaCh0aGlzLnJlYWRGbG9hdCgpKTtcbiAgICAgICAgcmV0dXJuIGFycjtcbiAgICB9LFxuICAgIHJlYWRQYWNrZWREb3VibGU6IGZ1bmN0aW9uKGFycikge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWREb3VibGUoKSk7XG4gICAgICAgIHZhciBlbmQgPSByZWFkUGFja2VkRW5kKHRoaXMpO1xuICAgICAgICBhcnIgPSBhcnIgfHwgW107XG4gICAgICAgIHdoaWxlICh0aGlzLnBvcyA8IGVuZCkgYXJyLnB1c2godGhpcy5yZWFkRG91YmxlKCkpO1xuICAgICAgICByZXR1cm4gYXJyO1xuICAgIH0sXG4gICAgcmVhZFBhY2tlZEZpeGVkMzI6IGZ1bmN0aW9uKGFycikge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWRGaXhlZDMyKCkpO1xuICAgICAgICB2YXIgZW5kID0gcmVhZFBhY2tlZEVuZCh0aGlzKTtcbiAgICAgICAgYXJyID0gYXJyIHx8IFtdO1xuICAgICAgICB3aGlsZSAodGhpcy5wb3MgPCBlbmQpIGFyci5wdXNoKHRoaXMucmVhZEZpeGVkMzIoKSk7XG4gICAgICAgIHJldHVybiBhcnI7XG4gICAgfSxcbiAgICByZWFkUGFja2VkU0ZpeGVkMzI6IGZ1bmN0aW9uKGFycikge1xuICAgICAgICBpZiAodGhpcy50eXBlICE9PSBQYmYuQnl0ZXMpIHJldHVybiBhcnIucHVzaCh0aGlzLnJlYWRTRml4ZWQzMigpKTtcbiAgICAgICAgdmFyIGVuZCA9IHJlYWRQYWNrZWRFbmQodGhpcyk7XG4gICAgICAgIGFyciA9IGFyciB8fCBbXTtcbiAgICAgICAgd2hpbGUgKHRoaXMucG9zIDwgZW5kKSBhcnIucHVzaCh0aGlzLnJlYWRTRml4ZWQzMigpKTtcbiAgICAgICAgcmV0dXJuIGFycjtcbiAgICB9LFxuICAgIHJlYWRQYWNrZWRGaXhlZDY0OiBmdW5jdGlvbihhcnIpIHtcbiAgICAgICAgaWYgKHRoaXMudHlwZSAhPT0gUGJmLkJ5dGVzKSByZXR1cm4gYXJyLnB1c2godGhpcy5yZWFkRml4ZWQ2NCgpKTtcbiAgICAgICAgdmFyIGVuZCA9IHJlYWRQYWNrZWRFbmQodGhpcyk7XG4gICAgICAgIGFyciA9IGFyciB8fCBbXTtcbiAgICAgICAgd2hpbGUgKHRoaXMucG9zIDwgZW5kKSBhcnIucHVzaCh0aGlzLnJlYWRGaXhlZDY0KCkpO1xuICAgICAgICByZXR1cm4gYXJyO1xuICAgIH0sXG4gICAgcmVhZFBhY2tlZFNGaXhlZDY0OiBmdW5jdGlvbihhcnIpIHtcbiAgICAgICAgaWYgKHRoaXMudHlwZSAhPT0gUGJmLkJ5dGVzKSByZXR1cm4gYXJyLnB1c2godGhpcy5yZWFkU0ZpeGVkNjQoKSk7XG4gICAgICAgIHZhciBlbmQgPSByZWFkUGFja2VkRW5kKHRoaXMpO1xuICAgICAgICBhcnIgPSBhcnIgfHwgW107XG4gICAgICAgIHdoaWxlICh0aGlzLnBvcyA8IGVuZCkgYXJyLnB1c2godGhpcy5yZWFkU0ZpeGVkNjQoKSk7XG4gICAgICAgIHJldHVybiBhcnI7XG4gICAgfSxcblxuICAgIHNraXA6IGZ1bmN0aW9uKHZhbCkge1xuICAgICAgICB2YXIgdHlwZSA9IHZhbCAmIDB4NztcbiAgICAgICAgaWYgKHR5cGUgPT09IFBiZi5WYXJpbnQpIHdoaWxlICh0aGlzLmJ1Zlt0aGlzLnBvcysrXSA+IDB4N2YpIHt9XG4gICAgICAgIGVsc2UgaWYgKHR5cGUgPT09IFBiZi5CeXRlcykgdGhpcy5wb3MgPSB0aGlzLnJlYWRWYXJpbnQoKSArIHRoaXMucG9zO1xuICAgICAgICBlbHNlIGlmICh0eXBlID09PSBQYmYuRml4ZWQzMikgdGhpcy5wb3MgKz0gNDtcbiAgICAgICAgZWxzZSBpZiAodHlwZSA9PT0gUGJmLkZpeGVkNjQpIHRoaXMucG9zICs9IDg7XG4gICAgICAgIGVsc2UgdGhyb3cgbmV3IEVycm9yKCdVbmltcGxlbWVudGVkIHR5cGU6ICcgKyB0eXBlKTtcbiAgICB9LFxuXG4gICAgLy8gPT09IFdSSVRJTkcgPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cblxuICAgIHdyaXRlVGFnOiBmdW5jdGlvbih0YWcsIHR5cGUpIHtcbiAgICAgICAgdGhpcy53cml0ZVZhcmludCgodGFnIDw8IDMpIHwgdHlwZSk7XG4gICAgfSxcblxuICAgIHJlYWxsb2M6IGZ1bmN0aW9uKG1pbikge1xuICAgICAgICB2YXIgbGVuZ3RoID0gdGhpcy5sZW5ndGggfHwgMTY7XG5cbiAgICAgICAgd2hpbGUgKGxlbmd0aCA8IHRoaXMucG9zICsgbWluKSBsZW5ndGggKj0gMjtcblxuICAgICAgICBpZiAobGVuZ3RoICE9PSB0aGlzLmxlbmd0aCkge1xuICAgICAgICAgICAgdmFyIGJ1ZiA9IG5ldyBVaW50OEFycmF5KGxlbmd0aCk7XG4gICAgICAgICAgICBidWYuc2V0KHRoaXMuYnVmKTtcbiAgICAgICAgICAgIHRoaXMuYnVmID0gYnVmO1xuICAgICAgICAgICAgdGhpcy5sZW5ndGggPSBsZW5ndGg7XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgZmluaXNoOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy5sZW5ndGggPSB0aGlzLnBvcztcbiAgICAgICAgdGhpcy5wb3MgPSAwO1xuICAgICAgICByZXR1cm4gdGhpcy5idWYuc3ViYXJyYXkoMCwgdGhpcy5sZW5ndGgpO1xuICAgIH0sXG5cbiAgICB3cml0ZUZpeGVkMzI6IGZ1bmN0aW9uKHZhbCkge1xuICAgICAgICB0aGlzLnJlYWxsb2MoNCk7XG4gICAgICAgIHdyaXRlSW50MzIodGhpcy5idWYsIHZhbCwgdGhpcy5wb3MpO1xuICAgICAgICB0aGlzLnBvcyArPSA0O1xuICAgIH0sXG5cbiAgICB3cml0ZVNGaXhlZDMyOiBmdW5jdGlvbih2YWwpIHtcbiAgICAgICAgdGhpcy5yZWFsbG9jKDQpO1xuICAgICAgICB3cml0ZUludDMyKHRoaXMuYnVmLCB2YWwsIHRoaXMucG9zKTtcbiAgICAgICAgdGhpcy5wb3MgKz0gNDtcbiAgICB9LFxuXG4gICAgd3JpdGVGaXhlZDY0OiBmdW5jdGlvbih2YWwpIHtcbiAgICAgICAgdGhpcy5yZWFsbG9jKDgpO1xuICAgICAgICB3cml0ZUludDMyKHRoaXMuYnVmLCB2YWwgJiAtMSwgdGhpcy5wb3MpO1xuICAgICAgICB3cml0ZUludDMyKHRoaXMuYnVmLCBNYXRoLmZsb29yKHZhbCAqIFNISUZUX1JJR0hUXzMyKSwgdGhpcy5wb3MgKyA0KTtcbiAgICAgICAgdGhpcy5wb3MgKz0gODtcbiAgICB9LFxuXG4gICAgd3JpdGVTRml4ZWQ2NDogZnVuY3Rpb24odmFsKSB7XG4gICAgICAgIHRoaXMucmVhbGxvYyg4KTtcbiAgICAgICAgd3JpdGVJbnQzMih0aGlzLmJ1ZiwgdmFsICYgLTEsIHRoaXMucG9zKTtcbiAgICAgICAgd3JpdGVJbnQzMih0aGlzLmJ1ZiwgTWF0aC5mbG9vcih2YWwgKiBTSElGVF9SSUdIVF8zMiksIHRoaXMucG9zICsgNCk7XG4gICAgICAgIHRoaXMucG9zICs9IDg7XG4gICAgfSxcblxuICAgIHdyaXRlVmFyaW50OiBmdW5jdGlvbih2YWwpIHtcbiAgICAgICAgdmFsID0gK3ZhbCB8fCAwO1xuXG4gICAgICAgIGlmICh2YWwgPiAweGZmZmZmZmYgfHwgdmFsIDwgMCkge1xuICAgICAgICAgICAgd3JpdGVCaWdWYXJpbnQodmFsLCB0aGlzKTtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMucmVhbGxvYyg0KTtcblxuICAgICAgICB0aGlzLmJ1Zlt0aGlzLnBvcysrXSA9ICAgICAgICAgICB2YWwgJiAweDdmICB8ICh2YWwgPiAweDdmID8gMHg4MCA6IDApOyBpZiAodmFsIDw9IDB4N2YpIHJldHVybjtcbiAgICAgICAgdGhpcy5idWZbdGhpcy5wb3MrK10gPSAoKHZhbCA+Pj49IDcpICYgMHg3ZikgfCAodmFsID4gMHg3ZiA/IDB4ODAgOiAwKTsgaWYgKHZhbCA8PSAweDdmKSByZXR1cm47XG4gICAgICAgIHRoaXMuYnVmW3RoaXMucG9zKytdID0gKCh2YWwgPj4+PSA3KSAmIDB4N2YpIHwgKHZhbCA+IDB4N2YgPyAweDgwIDogMCk7IGlmICh2YWwgPD0gMHg3ZikgcmV0dXJuO1xuICAgICAgICB0aGlzLmJ1Zlt0aGlzLnBvcysrXSA9ICAgKHZhbCA+Pj4gNykgJiAweDdmO1xuICAgIH0sXG5cbiAgICB3cml0ZVNWYXJpbnQ6IGZ1bmN0aW9uKHZhbCkge1xuICAgICAgICB0aGlzLndyaXRlVmFyaW50KHZhbCA8IDAgPyAtdmFsICogMiAtIDEgOiB2YWwgKiAyKTtcbiAgICB9LFxuXG4gICAgd3JpdGVCb29sZWFuOiBmdW5jdGlvbih2YWwpIHtcbiAgICAgICAgdGhpcy53cml0ZVZhcmludChCb29sZWFuKHZhbCkpO1xuICAgIH0sXG5cbiAgICB3cml0ZVN0cmluZzogZnVuY3Rpb24oc3RyKSB7XG4gICAgICAgIHN0ciA9IFN0cmluZyhzdHIpO1xuICAgICAgICB0aGlzLnJlYWxsb2Moc3RyLmxlbmd0aCAqIDQpO1xuXG4gICAgICAgIHRoaXMucG9zKys7IC8vIHJlc2VydmUgMSBieXRlIGZvciBzaG9ydCBzdHJpbmcgbGVuZ3RoXG5cbiAgICAgICAgdmFyIHN0YXJ0UG9zID0gdGhpcy5wb3M7XG4gICAgICAgIC8vIHdyaXRlIHRoZSBzdHJpbmcgZGlyZWN0bHkgdG8gdGhlIGJ1ZmZlciBhbmQgc2VlIGhvdyBtdWNoIHdhcyB3cml0dGVuXG4gICAgICAgIHRoaXMucG9zID0gd3JpdGVVdGY4KHRoaXMuYnVmLCBzdHIsIHRoaXMucG9zKTtcbiAgICAgICAgdmFyIGxlbiA9IHRoaXMucG9zIC0gc3RhcnRQb3M7XG5cbiAgICAgICAgaWYgKGxlbiA+PSAweDgwKSBtYWtlUm9vbUZvckV4dHJhTGVuZ3RoKHN0YXJ0UG9zLCBsZW4sIHRoaXMpO1xuXG4gICAgICAgIC8vIGZpbmFsbHksIHdyaXRlIHRoZSBtZXNzYWdlIGxlbmd0aCBpbiB0aGUgcmVzZXJ2ZWQgcGxhY2UgYW5kIHJlc3RvcmUgdGhlIHBvc2l0aW9uXG4gICAgICAgIHRoaXMucG9zID0gc3RhcnRQb3MgLSAxO1xuICAgICAgICB0aGlzLndyaXRlVmFyaW50KGxlbik7XG4gICAgICAgIHRoaXMucG9zICs9IGxlbjtcbiAgICB9LFxuXG4gICAgd3JpdGVGbG9hdDogZnVuY3Rpb24odmFsKSB7XG4gICAgICAgIHRoaXMucmVhbGxvYyg0KTtcbiAgICAgICAgaWVlZTc1NC53cml0ZSh0aGlzLmJ1ZiwgdmFsLCB0aGlzLnBvcywgdHJ1ZSwgMjMsIDQpO1xuICAgICAgICB0aGlzLnBvcyArPSA0O1xuICAgIH0sXG5cbiAgICB3cml0ZURvdWJsZTogZnVuY3Rpb24odmFsKSB7XG4gICAgICAgIHRoaXMucmVhbGxvYyg4KTtcbiAgICAgICAgaWVlZTc1NC53cml0ZSh0aGlzLmJ1ZiwgdmFsLCB0aGlzLnBvcywgdHJ1ZSwgNTIsIDgpO1xuICAgICAgICB0aGlzLnBvcyArPSA4O1xuICAgIH0sXG5cbiAgICB3cml0ZUJ5dGVzOiBmdW5jdGlvbihidWZmZXIpIHtcbiAgICAgICAgdmFyIGxlbiA9IGJ1ZmZlci5sZW5ndGg7XG4gICAgICAgIHRoaXMud3JpdGVWYXJpbnQobGVuKTtcbiAgICAgICAgdGhpcy5yZWFsbG9jKGxlbik7XG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbGVuOyBpKyspIHRoaXMuYnVmW3RoaXMucG9zKytdID0gYnVmZmVyW2ldO1xuICAgIH0sXG5cbiAgICB3cml0ZVJhd01lc3NhZ2U6IGZ1bmN0aW9uKGZuLCBvYmopIHtcbiAgICAgICAgdGhpcy5wb3MrKzsgLy8gcmVzZXJ2ZSAxIGJ5dGUgZm9yIHNob3J0IG1lc3NhZ2UgbGVuZ3RoXG5cbiAgICAgICAgLy8gd3JpdGUgdGhlIG1lc3NhZ2UgZGlyZWN0bHkgdG8gdGhlIGJ1ZmZlciBhbmQgc2VlIGhvdyBtdWNoIHdhcyB3cml0dGVuXG4gICAgICAgIHZhciBzdGFydFBvcyA9IHRoaXMucG9zO1xuICAgICAgICBmbihvYmosIHRoaXMpO1xuICAgICAgICB2YXIgbGVuID0gdGhpcy5wb3MgLSBzdGFydFBvcztcblxuICAgICAgICBpZiAobGVuID49IDB4ODApIG1ha2VSb29tRm9yRXh0cmFMZW5ndGgoc3RhcnRQb3MsIGxlbiwgdGhpcyk7XG5cbiAgICAgICAgLy8gZmluYWxseSwgd3JpdGUgdGhlIG1lc3NhZ2UgbGVuZ3RoIGluIHRoZSByZXNlcnZlZCBwbGFjZSBhbmQgcmVzdG9yZSB0aGUgcG9zaXRpb25cbiAgICAgICAgdGhpcy5wb3MgPSBzdGFydFBvcyAtIDE7XG4gICAgICAgIHRoaXMud3JpdGVWYXJpbnQobGVuKTtcbiAgICAgICAgdGhpcy5wb3MgKz0gbGVuO1xuICAgIH0sXG5cbiAgICB3cml0ZU1lc3NhZ2U6IGZ1bmN0aW9uKHRhZywgZm4sIG9iaikge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLkJ5dGVzKTtcbiAgICAgICAgdGhpcy53cml0ZVJhd01lc3NhZ2UoZm4sIG9iaik7XG4gICAgfSxcblxuICAgIHdyaXRlUGFja2VkVmFyaW50OiAgIGZ1bmN0aW9uKHRhZywgYXJyKSB7IGlmIChhcnIubGVuZ3RoKSB0aGlzLndyaXRlTWVzc2FnZSh0YWcsIHdyaXRlUGFja2VkVmFyaW50LCBhcnIpOyAgIH0sXG4gICAgd3JpdGVQYWNrZWRTVmFyaW50OiAgZnVuY3Rpb24odGFnLCBhcnIpIHsgaWYgKGFyci5sZW5ndGgpIHRoaXMud3JpdGVNZXNzYWdlKHRhZywgd3JpdGVQYWNrZWRTVmFyaW50LCBhcnIpOyAgfSxcbiAgICB3cml0ZVBhY2tlZEJvb2xlYW46ICBmdW5jdGlvbih0YWcsIGFycikgeyBpZiAoYXJyLmxlbmd0aCkgdGhpcy53cml0ZU1lc3NhZ2UodGFnLCB3cml0ZVBhY2tlZEJvb2xlYW4sIGFycik7ICB9LFxuICAgIHdyaXRlUGFja2VkRmxvYXQ6ICAgIGZ1bmN0aW9uKHRhZywgYXJyKSB7IGlmIChhcnIubGVuZ3RoKSB0aGlzLndyaXRlTWVzc2FnZSh0YWcsIHdyaXRlUGFja2VkRmxvYXQsIGFycik7ICAgIH0sXG4gICAgd3JpdGVQYWNrZWREb3VibGU6ICAgZnVuY3Rpb24odGFnLCBhcnIpIHsgaWYgKGFyci5sZW5ndGgpIHRoaXMud3JpdGVNZXNzYWdlKHRhZywgd3JpdGVQYWNrZWREb3VibGUsIGFycik7ICAgfSxcbiAgICB3cml0ZVBhY2tlZEZpeGVkMzI6ICBmdW5jdGlvbih0YWcsIGFycikgeyBpZiAoYXJyLmxlbmd0aCkgdGhpcy53cml0ZU1lc3NhZ2UodGFnLCB3cml0ZVBhY2tlZEZpeGVkMzIsIGFycik7ICB9LFxuICAgIHdyaXRlUGFja2VkU0ZpeGVkMzI6IGZ1bmN0aW9uKHRhZywgYXJyKSB7IGlmIChhcnIubGVuZ3RoKSB0aGlzLndyaXRlTWVzc2FnZSh0YWcsIHdyaXRlUGFja2VkU0ZpeGVkMzIsIGFycik7IH0sXG4gICAgd3JpdGVQYWNrZWRGaXhlZDY0OiAgZnVuY3Rpb24odGFnLCBhcnIpIHsgaWYgKGFyci5sZW5ndGgpIHRoaXMud3JpdGVNZXNzYWdlKHRhZywgd3JpdGVQYWNrZWRGaXhlZDY0LCBhcnIpOyAgfSxcbiAgICB3cml0ZVBhY2tlZFNGaXhlZDY0OiBmdW5jdGlvbih0YWcsIGFycikgeyBpZiAoYXJyLmxlbmd0aCkgdGhpcy53cml0ZU1lc3NhZ2UodGFnLCB3cml0ZVBhY2tlZFNGaXhlZDY0LCBhcnIpOyB9LFxuXG4gICAgd3JpdGVCeXRlc0ZpZWxkOiBmdW5jdGlvbih0YWcsIGJ1ZmZlcikge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLkJ5dGVzKTtcbiAgICAgICAgdGhpcy53cml0ZUJ5dGVzKGJ1ZmZlcik7XG4gICAgfSxcbiAgICB3cml0ZUZpeGVkMzJGaWVsZDogZnVuY3Rpb24odGFnLCB2YWwpIHtcbiAgICAgICAgdGhpcy53cml0ZVRhZyh0YWcsIFBiZi5GaXhlZDMyKTtcbiAgICAgICAgdGhpcy53cml0ZUZpeGVkMzIodmFsKTtcbiAgICB9LFxuICAgIHdyaXRlU0ZpeGVkMzJGaWVsZDogZnVuY3Rpb24odGFnLCB2YWwpIHtcbiAgICAgICAgdGhpcy53cml0ZVRhZyh0YWcsIFBiZi5GaXhlZDMyKTtcbiAgICAgICAgdGhpcy53cml0ZVNGaXhlZDMyKHZhbCk7XG4gICAgfSxcbiAgICB3cml0ZUZpeGVkNjRGaWVsZDogZnVuY3Rpb24odGFnLCB2YWwpIHtcbiAgICAgICAgdGhpcy53cml0ZVRhZyh0YWcsIFBiZi5GaXhlZDY0KTtcbiAgICAgICAgdGhpcy53cml0ZUZpeGVkNjQodmFsKTtcbiAgICB9LFxuICAgIHdyaXRlU0ZpeGVkNjRGaWVsZDogZnVuY3Rpb24odGFnLCB2YWwpIHtcbiAgICAgICAgdGhpcy53cml0ZVRhZyh0YWcsIFBiZi5GaXhlZDY0KTtcbiAgICAgICAgdGhpcy53cml0ZVNGaXhlZDY0KHZhbCk7XG4gICAgfSxcbiAgICB3cml0ZVZhcmludEZpZWxkOiBmdW5jdGlvbih0YWcsIHZhbCkge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLlZhcmludCk7XG4gICAgICAgIHRoaXMud3JpdGVWYXJpbnQodmFsKTtcbiAgICB9LFxuICAgIHdyaXRlU1ZhcmludEZpZWxkOiBmdW5jdGlvbih0YWcsIHZhbCkge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLlZhcmludCk7XG4gICAgICAgIHRoaXMud3JpdGVTVmFyaW50KHZhbCk7XG4gICAgfSxcbiAgICB3cml0ZVN0cmluZ0ZpZWxkOiBmdW5jdGlvbih0YWcsIHN0cikge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLkJ5dGVzKTtcbiAgICAgICAgdGhpcy53cml0ZVN0cmluZyhzdHIpO1xuICAgIH0sXG4gICAgd3JpdGVGbG9hdEZpZWxkOiBmdW5jdGlvbih0YWcsIHZhbCkge1xuICAgICAgICB0aGlzLndyaXRlVGFnKHRhZywgUGJmLkZpeGVkMzIpO1xuICAgICAgICB0aGlzLndyaXRlRmxvYXQodmFsKTtcbiAgICB9LFxuICAgIHdyaXRlRG91YmxlRmllbGQ6IGZ1bmN0aW9uKHRhZywgdmFsKSB7XG4gICAgICAgIHRoaXMud3JpdGVUYWcodGFnLCBQYmYuRml4ZWQ2NCk7XG4gICAgICAgIHRoaXMud3JpdGVEb3VibGUodmFsKTtcbiAgICB9LFxuICAgIHdyaXRlQm9vbGVhbkZpZWxkOiBmdW5jdGlvbih0YWcsIHZhbCkge1xuICAgICAgICB0aGlzLndyaXRlVmFyaW50RmllbGQodGFnLCBCb29sZWFuKHZhbCkpO1xuICAgIH1cbn07XG5cbmZ1bmN0aW9uIHJlYWRWYXJpbnRSZW1haW5kZXIobCwgcywgcCkge1xuICAgIHZhciBidWYgPSBwLmJ1ZixcbiAgICAgICAgaCwgYjtcblxuICAgIGIgPSBidWZbcC5wb3MrK107IGggID0gKGIgJiAweDcwKSA+PiA0OyAgaWYgKGIgPCAweDgwKSByZXR1cm4gdG9OdW0obCwgaCwgcyk7XG4gICAgYiA9IGJ1ZltwLnBvcysrXTsgaCB8PSAoYiAmIDB4N2YpIDw8IDM7ICBpZiAoYiA8IDB4ODApIHJldHVybiB0b051bShsLCBoLCBzKTtcbiAgICBiID0gYnVmW3AucG9zKytdOyBoIHw9IChiICYgMHg3ZikgPDwgMTA7IGlmIChiIDwgMHg4MCkgcmV0dXJuIHRvTnVtKGwsIGgsIHMpO1xuICAgIGIgPSBidWZbcC5wb3MrK107IGggfD0gKGIgJiAweDdmKSA8PCAxNzsgaWYgKGIgPCAweDgwKSByZXR1cm4gdG9OdW0obCwgaCwgcyk7XG4gICAgYiA9IGJ1ZltwLnBvcysrXTsgaCB8PSAoYiAmIDB4N2YpIDw8IDI0OyBpZiAoYiA8IDB4ODApIHJldHVybiB0b051bShsLCBoLCBzKTtcbiAgICBiID0gYnVmW3AucG9zKytdOyBoIHw9IChiICYgMHgwMSkgPDwgMzE7IGlmIChiIDwgMHg4MCkgcmV0dXJuIHRvTnVtKGwsIGgsIHMpO1xuXG4gICAgdGhyb3cgbmV3IEVycm9yKCdFeHBlY3RlZCB2YXJpbnQgbm90IG1vcmUgdGhhbiAxMCBieXRlcycpO1xufVxuXG5mdW5jdGlvbiByZWFkUGFja2VkRW5kKHBiZikge1xuICAgIHJldHVybiBwYmYudHlwZSA9PT0gUGJmLkJ5dGVzID9cbiAgICAgICAgcGJmLnJlYWRWYXJpbnQoKSArIHBiZi5wb3MgOiBwYmYucG9zICsgMTtcbn1cblxuZnVuY3Rpb24gdG9OdW0obG93LCBoaWdoLCBpc1NpZ25lZCkge1xuICAgIGlmIChpc1NpZ25lZCkge1xuICAgICAgICByZXR1cm4gaGlnaCAqIDB4MTAwMDAwMDAwICsgKGxvdyA+Pj4gMCk7XG4gICAgfVxuXG4gICAgcmV0dXJuICgoaGlnaCA+Pj4gMCkgKiAweDEwMDAwMDAwMCkgKyAobG93ID4+PiAwKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVCaWdWYXJpbnQodmFsLCBwYmYpIHtcbiAgICB2YXIgbG93LCBoaWdoO1xuXG4gICAgaWYgKHZhbCA+PSAwKSB7XG4gICAgICAgIGxvdyAgPSAodmFsICUgMHgxMDAwMDAwMDApIHwgMDtcbiAgICAgICAgaGlnaCA9ICh2YWwgLyAweDEwMDAwMDAwMCkgfCAwO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIGxvdyAgPSB+KC12YWwgJSAweDEwMDAwMDAwMCk7XG4gICAgICAgIGhpZ2ggPSB+KC12YWwgLyAweDEwMDAwMDAwMCk7XG5cbiAgICAgICAgaWYgKGxvdyBeIDB4ZmZmZmZmZmYpIHtcbiAgICAgICAgICAgIGxvdyA9IChsb3cgKyAxKSB8IDA7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsb3cgPSAwO1xuICAgICAgICAgICAgaGlnaCA9IChoaWdoICsgMSkgfCAwO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgaWYgKHZhbCA+PSAweDEwMDAwMDAwMDAwMDAwMDAwIHx8IHZhbCA8IC0weDEwMDAwMDAwMDAwMDAwMDAwKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcignR2l2ZW4gdmFyaW50IGRvZXNuXFwndCBmaXQgaW50byAxMCBieXRlcycpO1xuICAgIH1cblxuICAgIHBiZi5yZWFsbG9jKDEwKTtcblxuICAgIHdyaXRlQmlnVmFyaW50TG93KGxvdywgaGlnaCwgcGJmKTtcbiAgICB3cml0ZUJpZ1ZhcmludEhpZ2goaGlnaCwgcGJmKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVCaWdWYXJpbnRMb3cobG93LCBoaWdoLCBwYmYpIHtcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gPSBsb3cgJiAweDdmIHwgMHg4MDsgbG93ID4+Pj0gNztcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gPSBsb3cgJiAweDdmIHwgMHg4MDsgbG93ID4+Pj0gNztcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gPSBsb3cgJiAweDdmIHwgMHg4MDsgbG93ID4+Pj0gNztcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gPSBsb3cgJiAweDdmIHwgMHg4MDsgbG93ID4+Pj0gNztcbiAgICBwYmYuYnVmW3BiZi5wb3NdICAgPSBsb3cgJiAweDdmO1xufVxuXG5mdW5jdGlvbiB3cml0ZUJpZ1ZhcmludEhpZ2goaGlnaCwgcGJmKSB7XG4gICAgdmFyIGxzYiA9IChoaWdoICYgMHgwNykgPDwgNDtcblxuICAgIHBiZi5idWZbcGJmLnBvcysrXSB8PSBsc2IgICAgICAgICB8ICgoaGlnaCA+Pj49IDMpID8gMHg4MCA6IDApOyBpZiAoIWhpZ2gpIHJldHVybjtcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gID0gaGlnaCAmIDB4N2YgfCAoKGhpZ2ggPj4+PSA3KSA/IDB4ODAgOiAwKTsgaWYgKCFoaWdoKSByZXR1cm47XG4gICAgcGJmLmJ1ZltwYmYucG9zKytdICA9IGhpZ2ggJiAweDdmIHwgKChoaWdoID4+Pj0gNykgPyAweDgwIDogMCk7IGlmICghaGlnaCkgcmV0dXJuO1xuICAgIHBiZi5idWZbcGJmLnBvcysrXSAgPSBoaWdoICYgMHg3ZiB8ICgoaGlnaCA+Pj49IDcpID8gMHg4MCA6IDApOyBpZiAoIWhpZ2gpIHJldHVybjtcbiAgICBwYmYuYnVmW3BiZi5wb3MrK10gID0gaGlnaCAmIDB4N2YgfCAoKGhpZ2ggPj4+PSA3KSA/IDB4ODAgOiAwKTsgaWYgKCFoaWdoKSByZXR1cm47XG4gICAgcGJmLmJ1ZltwYmYucG9zKytdICA9IGhpZ2ggJiAweDdmO1xufVxuXG5mdW5jdGlvbiBtYWtlUm9vbUZvckV4dHJhTGVuZ3RoKHN0YXJ0UG9zLCBsZW4sIHBiZikge1xuICAgIHZhciBleHRyYUxlbiA9XG4gICAgICAgIGxlbiA8PSAweDNmZmYgPyAxIDpcbiAgICAgICAgbGVuIDw9IDB4MWZmZmZmID8gMiA6XG4gICAgICAgIGxlbiA8PSAweGZmZmZmZmYgPyAzIDogTWF0aC5mbG9vcihNYXRoLmxvZyhsZW4pIC8gKE1hdGguTE4yICogNykpO1xuXG4gICAgLy8gaWYgMSBieXRlIGlzbid0IGVub3VnaCBmb3IgZW5jb2RpbmcgbWVzc2FnZSBsZW5ndGgsIHNoaWZ0IHRoZSBkYXRhIHRvIHRoZSByaWdodFxuICAgIHBiZi5yZWFsbG9jKGV4dHJhTGVuKTtcbiAgICBmb3IgKHZhciBpID0gcGJmLnBvcyAtIDE7IGkgPj0gc3RhcnRQb3M7IGktLSkgcGJmLmJ1ZltpICsgZXh0cmFMZW5dID0gcGJmLmJ1ZltpXTtcbn1cblxuZnVuY3Rpb24gd3JpdGVQYWNrZWRWYXJpbnQoYXJyLCBwYmYpICAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlVmFyaW50KGFycltpXSk7ICAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRTVmFyaW50KGFyciwgcGJmKSAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlU1ZhcmludChhcnJbaV0pOyAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRGbG9hdChhcnIsIHBiZikgICAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlRmxvYXQoYXJyW2ldKTsgICAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWREb3VibGUoYXJyLCBwYmYpICAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlRG91YmxlKGFycltpXSk7ICAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRCb29sZWFuKGFyciwgcGJmKSAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlQm9vbGVhbihhcnJbaV0pOyAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRGaXhlZDMyKGFyciwgcGJmKSAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlRml4ZWQzMihhcnJbaV0pOyAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRTRml4ZWQzMihhcnIsIHBiZikgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlU0ZpeGVkMzIoYXJyW2ldKTsgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRGaXhlZDY0KGFyciwgcGJmKSAgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlRml4ZWQ2NChhcnJbaV0pOyAgfVxuZnVuY3Rpb24gd3JpdGVQYWNrZWRTRml4ZWQ2NChhcnIsIHBiZikgeyBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykgcGJmLndyaXRlU0ZpeGVkNjQoYXJyW2ldKTsgfVxuXG4vLyBCdWZmZXIgY29kZSBiZWxvdyBmcm9tIGh0dHBzOi8vZ2l0aHViLmNvbS9mZXJvc3MvYnVmZmVyLCBNSVQtbGljZW5zZWRcblxuZnVuY3Rpb24gcmVhZFVJbnQzMihidWYsIHBvcykge1xuICAgIHJldHVybiAoKGJ1Zltwb3NdKSB8XG4gICAgICAgIChidWZbcG9zICsgMV0gPDwgOCkgfFxuICAgICAgICAoYnVmW3BvcyArIDJdIDw8IDE2KSkgK1xuICAgICAgICAoYnVmW3BvcyArIDNdICogMHgxMDAwMDAwKTtcbn1cblxuZnVuY3Rpb24gd3JpdGVJbnQzMihidWYsIHZhbCwgcG9zKSB7XG4gICAgYnVmW3Bvc10gPSB2YWw7XG4gICAgYnVmW3BvcyArIDFdID0gKHZhbCA+Pj4gOCk7XG4gICAgYnVmW3BvcyArIDJdID0gKHZhbCA+Pj4gMTYpO1xuICAgIGJ1Zltwb3MgKyAzXSA9ICh2YWwgPj4+IDI0KTtcbn1cblxuZnVuY3Rpb24gcmVhZEludDMyKGJ1ZiwgcG9zKSB7XG4gICAgcmV0dXJuICgoYnVmW3Bvc10pIHxcbiAgICAgICAgKGJ1Zltwb3MgKyAxXSA8PCA4KSB8XG4gICAgICAgIChidWZbcG9zICsgMl0gPDwgMTYpKSArXG4gICAgICAgIChidWZbcG9zICsgM10gPDwgMjQpO1xufVxuXG5mdW5jdGlvbiByZWFkVXRmOChidWYsIHBvcywgZW5kKSB7XG4gICAgdmFyIHN0ciA9ICcnO1xuICAgIHZhciBpID0gcG9zO1xuXG4gICAgd2hpbGUgKGkgPCBlbmQpIHtcbiAgICAgICAgdmFyIGIwID0gYnVmW2ldO1xuICAgICAgICB2YXIgYyA9IG51bGw7IC8vIGNvZGVwb2ludFxuICAgICAgICB2YXIgYnl0ZXNQZXJTZXF1ZW5jZSA9XG4gICAgICAgICAgICBiMCA+IDB4RUYgPyA0IDpcbiAgICAgICAgICAgIGIwID4gMHhERiA/IDMgOlxuICAgICAgICAgICAgYjAgPiAweEJGID8gMiA6IDE7XG5cbiAgICAgICAgaWYgKGkgKyBieXRlc1BlclNlcXVlbmNlID4gZW5kKSBicmVhaztcblxuICAgICAgICB2YXIgYjEsIGIyLCBiMztcblxuICAgICAgICBpZiAoYnl0ZXNQZXJTZXF1ZW5jZSA9PT0gMSkge1xuICAgICAgICAgICAgaWYgKGIwIDwgMHg4MCkge1xuICAgICAgICAgICAgICAgIGMgPSBiMDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSBlbHNlIGlmIChieXRlc1BlclNlcXVlbmNlID09PSAyKSB7XG4gICAgICAgICAgICBiMSA9IGJ1ZltpICsgMV07XG4gICAgICAgICAgICBpZiAoKGIxICYgMHhDMCkgPT09IDB4ODApIHtcbiAgICAgICAgICAgICAgICBjID0gKGIwICYgMHgxRikgPDwgMHg2IHwgKGIxICYgMHgzRik7XG4gICAgICAgICAgICAgICAgaWYgKGMgPD0gMHg3Rikge1xuICAgICAgICAgICAgICAgICAgICBjID0gbnVsbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0gZWxzZSBpZiAoYnl0ZXNQZXJTZXF1ZW5jZSA9PT0gMykge1xuICAgICAgICAgICAgYjEgPSBidWZbaSArIDFdO1xuICAgICAgICAgICAgYjIgPSBidWZbaSArIDJdO1xuICAgICAgICAgICAgaWYgKChiMSAmIDB4QzApID09PSAweDgwICYmIChiMiAmIDB4QzApID09PSAweDgwKSB7XG4gICAgICAgICAgICAgICAgYyA9IChiMCAmIDB4RikgPDwgMHhDIHwgKGIxICYgMHgzRikgPDwgMHg2IHwgKGIyICYgMHgzRik7XG4gICAgICAgICAgICAgICAgaWYgKGMgPD0gMHg3RkYgfHwgKGMgPj0gMHhEODAwICYmIGMgPD0gMHhERkZGKSkge1xuICAgICAgICAgICAgICAgICAgICBjID0gbnVsbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0gZWxzZSBpZiAoYnl0ZXNQZXJTZXF1ZW5jZSA9PT0gNCkge1xuICAgICAgICAgICAgYjEgPSBidWZbaSArIDFdO1xuICAgICAgICAgICAgYjIgPSBidWZbaSArIDJdO1xuICAgICAgICAgICAgYjMgPSBidWZbaSArIDNdO1xuICAgICAgICAgICAgaWYgKChiMSAmIDB4QzApID09PSAweDgwICYmIChiMiAmIDB4QzApID09PSAweDgwICYmIChiMyAmIDB4QzApID09PSAweDgwKSB7XG4gICAgICAgICAgICAgICAgYyA9IChiMCAmIDB4RikgPDwgMHgxMiB8IChiMSAmIDB4M0YpIDw8IDB4QyB8IChiMiAmIDB4M0YpIDw8IDB4NiB8IChiMyAmIDB4M0YpO1xuICAgICAgICAgICAgICAgIGlmIChjIDw9IDB4RkZGRiB8fCBjID49IDB4MTEwMDAwKSB7XG4gICAgICAgICAgICAgICAgICAgIGMgPSBudWxsO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChjID09PSBudWxsKSB7XG4gICAgICAgICAgICBjID0gMHhGRkZEO1xuICAgICAgICAgICAgYnl0ZXNQZXJTZXF1ZW5jZSA9IDE7XG5cbiAgICAgICAgfSBlbHNlIGlmIChjID4gMHhGRkZGKSB7XG4gICAgICAgICAgICBjIC09IDB4MTAwMDA7XG4gICAgICAgICAgICBzdHIgKz0gU3RyaW5nLmZyb21DaGFyQ29kZShjID4+PiAxMCAmIDB4M0ZGIHwgMHhEODAwKTtcbiAgICAgICAgICAgIGMgPSAweERDMDAgfCBjICYgMHgzRkY7XG4gICAgICAgIH1cblxuICAgICAgICBzdHIgKz0gU3RyaW5nLmZyb21DaGFyQ29kZShjKTtcbiAgICAgICAgaSArPSBieXRlc1BlclNlcXVlbmNlO1xuICAgIH1cblxuICAgIHJldHVybiBzdHI7XG59XG5cbmZ1bmN0aW9uIHJlYWRVdGY4VGV4dERlY29kZXIoYnVmLCBwb3MsIGVuZCkge1xuICAgIHJldHVybiB1dGY4VGV4dERlY29kZXIuZGVjb2RlKGJ1Zi5zdWJhcnJheShwb3MsIGVuZCkpO1xufVxuXG5mdW5jdGlvbiB3cml0ZVV0ZjgoYnVmLCBzdHIsIHBvcykge1xuICAgIGZvciAodmFyIGkgPSAwLCBjLCBsZWFkOyBpIDwgc3RyLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGMgPSBzdHIuY2hhckNvZGVBdChpKTsgLy8gY29kZSBwb2ludFxuXG4gICAgICAgIGlmIChjID4gMHhEN0ZGICYmIGMgPCAweEUwMDApIHtcbiAgICAgICAgICAgIGlmIChsZWFkKSB7XG4gICAgICAgICAgICAgICAgaWYgKGMgPCAweERDMDApIHtcbiAgICAgICAgICAgICAgICAgICAgYnVmW3BvcysrXSA9IDB4RUY7XG4gICAgICAgICAgICAgICAgICAgIGJ1Zltwb3MrK10gPSAweEJGO1xuICAgICAgICAgICAgICAgICAgICBidWZbcG9zKytdID0gMHhCRDtcbiAgICAgICAgICAgICAgICAgICAgbGVhZCA9IGM7XG4gICAgICAgICAgICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGMgPSBsZWFkIC0gMHhEODAwIDw8IDEwIHwgYyAtIDB4REMwMCB8IDB4MTAwMDA7XG4gICAgICAgICAgICAgICAgICAgIGxlYWQgPSBudWxsO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgaWYgKGMgPiAweERCRkYgfHwgKGkgKyAxID09PSBzdHIubGVuZ3RoKSkge1xuICAgICAgICAgICAgICAgICAgICBidWZbcG9zKytdID0gMHhFRjtcbiAgICAgICAgICAgICAgICAgICAgYnVmW3BvcysrXSA9IDB4QkY7XG4gICAgICAgICAgICAgICAgICAgIGJ1Zltwb3MrK10gPSAweEJEO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGxlYWQgPSBjO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSBlbHNlIGlmIChsZWFkKSB7XG4gICAgICAgICAgICBidWZbcG9zKytdID0gMHhFRjtcbiAgICAgICAgICAgIGJ1Zltwb3MrK10gPSAweEJGO1xuICAgICAgICAgICAgYnVmW3BvcysrXSA9IDB4QkQ7XG4gICAgICAgICAgICBsZWFkID0gbnVsbDtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChjIDwgMHg4MCkge1xuICAgICAgICAgICAgYnVmW3BvcysrXSA9IGM7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBpZiAoYyA8IDB4ODAwKSB7XG4gICAgICAgICAgICAgICAgYnVmW3BvcysrXSA9IGMgPj4gMHg2IHwgMHhDMDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgaWYgKGMgPCAweDEwMDAwKSB7XG4gICAgICAgICAgICAgICAgICAgIGJ1Zltwb3MrK10gPSBjID4+IDB4QyB8IDB4RTA7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgYnVmW3BvcysrXSA9IGMgPj4gMHgxMiB8IDB4RjA7XG4gICAgICAgICAgICAgICAgICAgIGJ1Zltwb3MrK10gPSBjID4+IDB4QyAmIDB4M0YgfCAweDgwO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBidWZbcG9zKytdID0gYyA+PiAweDYgJiAweDNGIHwgMHg4MDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGJ1Zltwb3MrK10gPSBjICYgMHgzRiB8IDB4ODA7XG4gICAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIHBvcztcbn1cbiIsImltcG9ydCB7IGRlY29kZSB9IGZyb20gXCJnZW9idWZcIjtcbmltcG9ydCBpbnNpZGUgZnJvbSBcIkB0dXJmL2Jvb2xlYW4tcG9pbnQtaW4tcG9seWdvblwiO1xuaW1wb3J0IHsgcG9pbnQgfSBmcm9tIFwiQHR1cmYvaGVscGVyc1wiO1xuaW1wb3J0IFBiZiBmcm9tIFwicGJmXCI7XG5cbmltcG9ydCB7IGdldFRpbWV6b25lQXRTZWEsIG9jZWFuWm9uZXMgfSBmcm9tIFwiLi9vY2VhblV0aWxzXCI7XG5cbnR5cGUgR2VvRGF0YVNvdXJjZSA9XG4gIHwgc3RyaW5nXG4gIHwgKChzdGFydDogbnVtYmVyLCBlbmQ6IG51bWJlcikgPT4gUHJvbWlzZTxBcnJheUJ1ZmZlcj4pO1xudHlwZSBUekRhdGFTb3VyY2UgPSBzdHJpbmcgfCAoKCkgPT4gUHJvbWlzZTxhbnk+KTtcblxuLyoqXG4gKiBJbml0aWFsaXplIHRoZSBHZW9UWiBtb2R1bGUgd2l0aCB0aGUgZ2l2ZW4gZGF0YSBzb3VyY2VzLlxuICpcbiAqIEBwYXJhbSBnZW9EYXRhU291cmNlIEEgc3RyaW5nIG9mIHRoZSBVUkwgb2YgdGhlIEdlb0pTT04gZGF0YSBvciBhIGZ1bmN0aW9uIHRoYXQgcmV0dXJucyBhbiBBcnJheUJ1ZmZlciBnaXZlbiBhIGJ5dGUgcmFuZ2UuXG4gKiBAcGFyYW0gdHpEYXRhU291cmNlIEEgc3RyaW5nIG9mIHRoZSBVUkwgb2YgdGhlIGluZGV4Lmpzb24gZGF0YSBvciBhIGZ1bmN0aW9uIHRoYXQgcmV0dXJucyBhbiBvYmplY3QuXG4gKiBAcmV0dXJucyBBbiBvYmplY3Qgd2l0aCBhIGZpbmQgZnVuY3Rpb24gdGhhdCBjYW4gYmUgdXNlZCB0byBmaW5kIHRoZSB0aW1lem9uZSBJRChzKSBhdCB0aGUgZ2l2ZW4gR1BTIGNvb3JkaW5hdGVzLlxuICovXG5leHBvcnQgZnVuY3Rpb24gaW5pdChcbiAgZ2VvRGF0YVNvdXJjZTogR2VvRGF0YVNvdXJjZSA9IFwiaHR0cHM6Ly9jZG4uanNkZWxpdnIubmV0L25wbS9nZW8tdHpAbGF0ZXN0L2RhdGEvdGltZXpvbmVzLTE5NzAuZ2VvanNvbi5nZW8uZGF0XCIsXG4gIHR6RGF0YVNvdXJjZTogVHpEYXRhU291cmNlID0gXCJodHRwczovL2Nkbi5qc2RlbGl2ci5uZXQvbnBtL2dlby10ekBsYXRlc3QvZGF0YS90aW1lem9uZXMtMTk3MC5nZW9qc29uLmluZGV4Lmpzb25cIixcbikge1xuICBjb25zdCBnZW9EYXRhID1cbiAgICB0eXBlb2YgZ2VvRGF0YVNvdXJjZSA9PT0gXCJzdHJpbmdcIlxuICAgICAgPyBhc3luYyAoc3RhcnQ6IG51bWJlciwgZW5kOiBudW1iZXIpID0+IHtcbiAgICAgICAgICBjb25zdCByZXNwb25zZSA9IGF3YWl0IGZldGNoKGdlb0RhdGFTb3VyY2UsIHtcbiAgICAgICAgICAgIGhlYWRlcnM6IHsgUmFuZ2U6IGBieXRlcz0ke3N0YXJ0fS0ke2VuZH1gIH0sXG4gICAgICAgICAgfSk7XG4gICAgICAgICAgcmV0dXJuIGF3YWl0IHJlc3BvbnNlLmFycmF5QnVmZmVyKCk7XG4gICAgICAgIH1cbiAgICAgIDogZ2VvRGF0YVNvdXJjZTtcblxuICBsZXQgdHpEYXRhUHJvbWlzZTogUHJvbWlzZTxhbnk+IHwgbnVsbCA9IG51bGw7XG5cbiAgY29uc3QgdHpEYXRhID1cbiAgICB0eXBlb2YgdHpEYXRhU291cmNlID09PSBcInN0cmluZ1wiXG4gICAgICA/IGFzeW5jICgpID0+IHtcbiAgICAgICAgICBpZiAodHpEYXRhUHJvbWlzZSkge1xuICAgICAgICAgICAgcmV0dXJuIGF3YWl0IHR6RGF0YVByb21pc2U7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNvbnN0IHByb21pc2UgPSBmZXRjaCh0ekRhdGFTb3VyY2UpLnRoZW4oKHJlc3BvbnNlKSA9PlxuICAgICAgICAgICAgcmVzcG9uc2UuanNvbigpLFxuICAgICAgICAgICk7XG4gICAgICAgICAgdHpEYXRhUHJvbWlzZSA9IHByb21pc2U7XG4gICAgICAgICAgcmV0dXJuIGF3YWl0IHByb21pc2U7XG4gICAgICAgIH1cbiAgICAgIDogdHpEYXRhU291cmNlO1xuXG4gIHJldHVybiB7XG4gICAgLyoqXG4gICAgICogRmluZCB0aGUgdGltZXpvbmUgSUQocykgYXQgdGhlIGdpdmVuIEdQUyBjb29yZGluYXRlcy5cbiAgICAgKlxuICAgICAqIEBwYXJhbSBsYXQgbGF0aXR1ZGUgKG11c3QgYmUgPj0gLTkwIGFuZCA8PTkwKVxuICAgICAqIEBwYXJhbSBsb24gbG9uZ2l0dWUgKG11c3QgYmUgPj0gLTE4MCBhbmQgPD0xODApXG4gICAgICogQHJldHVybnMgQW4gYXJyYXkgb2Ygc3RyaW5nIG9mIFRaSURzIGF0IHRoZSBnaXZlbiBjb29yZGluYXRlLlxuICAgICAqL1xuICAgIGZpbmQ6IGFzeW5jIChsYXQ6IG51bWJlciwgbG9uOiBudW1iZXIpID0+IHtcbiAgICAgIHJldHVybiBhd2FpdCBmaW5kSW1wbChnZW9EYXRhLCB0ekRhdGEsIGxhdCwgbG9uKTtcbiAgICB9LFxuICB9O1xufVxuXG4vKipcbiAqIEZpbmQgdGhlIHRpbWV6b25lIElEKHMpIGF0IHRoZSBnaXZlbiBHUFMgY29vcmRpbmF0ZXMuIFRoaXMgaXMgaWRlbnRpY2FsIHRvIGNhbGxpbmdcbiAqIGBpbml0KClgIGFuZCB0aGVuIGNhbGxpbmcgYGZpbmQoKWAuXG4gKlxuICogQHBhcmFtIGxhdCBsYXRpdHVkZSAobXVzdCBiZSA+PSAtOTAgYW5kIDw9OTApXG4gKiBAcGFyYW0gbG9uIGxvbmdpdHVlIChtdXN0IGJlID49IC0xODAgYW5kIDw9MTgwKVxuICogQHJldHVybnMgQW4gYXJyYXkgb2Ygc3RyaW5nIG9mIFRaSURzIGF0IHRoZSBnaXZlbiBjb29yZGluYXRlLlxuICovXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gZmluZChsYXQ6IG51bWJlciwgbG9uOiBudW1iZXIpOiBQcm9taXNlPHN0cmluZ1tdPiB7XG4gIHJldHVybiBhd2FpdCBpbml0KCkuZmluZChsYXQsIGxvbik7XG59XG5cbmFzeW5jIGZ1bmN0aW9uIGZpbmRJbXBsKFxuICBnZW9EYXRhOiAoc3RhcnQ6IG51bWJlciwgZW5kOiBudW1iZXIpID0+IFByb21pc2U8QXJyYXlCdWZmZXI+LFxuICB0ekRhdGE6ICgpID0+IFByb21pc2U8YW55PixcbiAgbGF0OiBudW1iZXIsXG4gIGxvbjogbnVtYmVyLFxuKTogUHJvbWlzZTxzdHJpbmdbXT4ge1xuICBjb25zdCBvcmlnaW5hbExvbiA9IGxvbjtcblxuICBsZXQgZXJyO1xuXG4gIC8vIHZhbGlkYXRlIGxhdGl0dWRlXG4gIGlmIChpc05hTihsYXQpIHx8IGxhdCA+IDkwIHx8IGxhdCA8IC05MCkge1xuICAgIGVyciA9IG5ldyBFcnJvcihcIkludmFsaWQgbGF0aXR1ZGU6IFwiICsgbGF0KTtcbiAgICB0aHJvdyBlcnI7XG4gIH1cblxuICAvLyB2YWxpZGF0ZSBsb25naXR1ZGVcbiAgaWYgKGlzTmFOKGxvbikgfHwgbG9uID4gMTgwIHx8IGxvbiA8IC0xODApIHtcbiAgICBlcnIgPSBuZXcgRXJyb3IoXCJJbnZhbGlkIGxvbmdpdHVkZTogXCIgKyBsb24pO1xuICAgIHRocm93IGVycjtcbiAgfVxuXG4gIC8vIE5vcnRoIFBvbGUgc2hvdWxkIHJldHVybiBhbGwgb2NlYW4gem9uZXNcbiAgaWYgKGxhdCA9PT0gOTApIHtcbiAgICByZXR1cm4gb2NlYW5ab25lcy5tYXAoKHpvbmUpID0+IHpvbmUudHppZCk7XG4gIH1cblxuICAvLyBmaXggZWRnZXMgb2YgdGhlIHdvcmxkXG4gIGlmIChsYXQgPj0gODkuOTk5OSkge1xuICAgIGxhdCA9IDg5Ljk5OTk7XG4gIH0gZWxzZSBpZiAobGF0IDw9IC04OS45OTk5KSB7XG4gICAgbGF0ID0gLTg5Ljk5OTk7XG4gIH1cblxuICBpZiAobG9uID49IDE3OS45OTk5KSB7XG4gICAgbG9uID0gMTc5Ljk5OTk7XG4gIH0gZWxzZSBpZiAobG9uIDw9IC0xNzkuOTk5OSkge1xuICAgIGxvbiA9IC0xNzkuOTk5OTtcbiAgfVxuXG4gIGNvbnN0IHB0ID0gcG9pbnQoW2xvbiwgbGF0XSk7XG5cbiAgLy8gZ2V0IGV4YWN0IGJvdW5kYXJpZXNcbiAgY29uc3QgcXVhZERhdGEgPSB7XG4gICAgdG9wOiA4OS45OTk5LFxuICAgIGJvdHRvbTogLTg5Ljk5OTksXG4gICAgbGVmdDogLTE3OS45OTk5LFxuICAgIHJpZ2h0OiAxNzkuOTk5OSxcbiAgICBtaWRMYXQ6IDAsXG4gICAgbWlkTG9uOiAwLFxuICB9O1xuICBsZXQgcXVhZFBvcyA9IFwiXCI7XG5cbiAgY29uc3QgdHpEYXRhUmVzcG9uc2UgPSBhd2FpdCB0ekRhdGEoKTtcblxuICBsZXQgY3VyVHpEYXRhID0gdHpEYXRhUmVzcG9uc2UubG9va3VwO1xuXG4gIHdoaWxlICh0cnVlKSB7XG4gICAgLy8gY2FsY3VsYXRlIG5leHQgcXVhZHRyZWUgcG9zaXRpb25cbiAgICBsZXQgbmV4dFF1YWQ7XG4gICAgaWYgKGxhdCA+PSBxdWFkRGF0YS5taWRMYXQgJiYgbG9uID49IHF1YWREYXRhLm1pZExvbikge1xuICAgICAgbmV4dFF1YWQgPSBcImFcIjtcbiAgICAgIHF1YWREYXRhLmJvdHRvbSA9IHF1YWREYXRhLm1pZExhdDtcbiAgICAgIHF1YWREYXRhLmxlZnQgPSBxdWFkRGF0YS5taWRMb247XG4gICAgfSBlbHNlIGlmIChsYXQgPj0gcXVhZERhdGEubWlkTGF0ICYmIGxvbiA8IHF1YWREYXRhLm1pZExvbikge1xuICAgICAgbmV4dFF1YWQgPSBcImJcIjtcbiAgICAgIHF1YWREYXRhLmJvdHRvbSA9IHF1YWREYXRhLm1pZExhdDtcbiAgICAgIHF1YWREYXRhLnJpZ2h0ID0gcXVhZERhdGEubWlkTG9uO1xuICAgIH0gZWxzZSBpZiAobGF0IDwgcXVhZERhdGEubWlkTGF0ICYmIGxvbiA8IHF1YWREYXRhLm1pZExvbikge1xuICAgICAgbmV4dFF1YWQgPSBcImNcIjtcbiAgICAgIHF1YWREYXRhLnRvcCA9IHF1YWREYXRhLm1pZExhdDtcbiAgICAgIHF1YWREYXRhLnJpZ2h0ID0gcXVhZERhdGEubWlkTG9uO1xuICAgIH0gZWxzZSB7XG4gICAgICBuZXh0UXVhZCA9IFwiZFwiO1xuICAgICAgcXVhZERhdGEudG9wID0gcXVhZERhdGEubWlkTGF0O1xuICAgICAgcXVhZERhdGEubGVmdCA9IHF1YWREYXRhLm1pZExvbjtcbiAgICB9XG5cbiAgICAvLyBjb25zb2xlLmxvZyhuZXh0UXVhZClcbiAgICBjdXJUekRhdGEgPSBjdXJUekRhdGFbbmV4dFF1YWRdO1xuICAgIC8vIGNvbnNvbGUubG9nKClcbiAgICBxdWFkUG9zICs9IG5leHRRdWFkO1xuXG4gICAgLy8gYW5hbHl6ZSByZXN1bHQgb2YgY3VycmVudCBkZXB0aFxuICAgIGlmICghY3VyVHpEYXRhKSB7XG4gICAgICAvLyBubyB0aW1lem9uZSBpbiB0aGlzIHF1YWQsIHRoZXJlZm9yZSBtdXN0IGJlIHRpbWV6b25lIGF0IHNlYVxuICAgICAgcmV0dXJuIGdldFRpbWV6b25lQXRTZWEob3JpZ2luYWxMb24pO1xuICAgIH0gZWxzZSBpZiAoY3VyVHpEYXRhLnBvcyA+PSAwICYmIGN1clR6RGF0YS5sZW4pIHtcbiAgICAgIC8vIGdldCBleGFjdCBib3VuZGFyaWVzXG4gICAgICBjb25zdCBidWZTbGljZSA9IGF3YWl0IGdlb0RhdGEoXG4gICAgICAgIGN1clR6RGF0YS5wb3MsXG4gICAgICAgIGN1clR6RGF0YS5wb3MgKyBjdXJUekRhdGEubGVuIC0gMSxcbiAgICAgICk7XG4gICAgICBjb25zdCBnZW9Kc29uID0gZGVjb2RlKG5ldyBQYmYoYnVmU2xpY2UpKTtcblxuICAgICAgY29uc3QgdGltZXpvbmVzQ29udGFpbmluZ1BvaW50ID0gW107XG5cbiAgICAgIGlmIChnZW9Kc29uLnR5cGUgPT09IFwiRmVhdHVyZUNvbGxlY3Rpb25cIikge1xuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IGdlb0pzb24uZmVhdHVyZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICBpZiAoaW5zaWRlKHB0LCBnZW9Kc29uLmZlYXR1cmVzW2ldIGFzIGFueSkpIHtcbiAgICAgICAgICAgIHRpbWV6b25lc0NvbnRhaW5pbmdQb2ludC5wdXNoKGdlb0pzb24uZmVhdHVyZXNbaV0ucHJvcGVydGllcy50emlkKTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAoZ2VvSnNvbi50eXBlID09PSBcIkZlYXR1cmVcIikge1xuICAgICAgICBpZiAoaW5zaWRlKHB0LCBnZW9Kc29uIGFzIGFueSkpIHtcbiAgICAgICAgICB0aW1lem9uZXNDb250YWluaW5nUG9pbnQucHVzaChnZW9Kc29uLnByb3BlcnRpZXMudHppZCk7XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgLy8gaWYgYXQgbGVhc3Qgb25lIHRpbWV6b25lIGNvbnRhaW5lZCB0aGUgcG9pbnQsIHJldHVybiB0aG9zZSB0aW1lem9uZXMsXG4gICAgICAvLyBvdGhlcndpc2UgbXVzdCBiZSB0aW1lem9uZSBhdCBzZWFcbiAgICAgIHJldHVybiB0aW1lem9uZXNDb250YWluaW5nUG9pbnQubGVuZ3RoID4gMFxuICAgICAgICA/IHRpbWV6b25lc0NvbnRhaW5pbmdQb2ludFxuICAgICAgICA6IGdldFRpbWV6b25lQXRTZWEob3JpZ2luYWxMb24pO1xuICAgIH0gZWxzZSBpZiAoY3VyVHpEYXRhLmxlbmd0aCA+IDApIHtcbiAgICAgIC8vIGV4YWN0IG1hdGNoIGZvdW5kXG4gICAgICBjb25zdCB0aW1lem9uZXMgPSB0ekRhdGFSZXNwb25zZS50aW1lem9uZXM7XG4gICAgICByZXR1cm4gY3VyVHpEYXRhLm1hcCgoaWR4KSA9PiB0aW1lem9uZXNbaWR4XSk7XG4gICAgfSBlbHNlIGlmICh0eXBlb2YgY3VyVHpEYXRhICE9PSBcIm9iamVjdFwiKSB7XG4gICAgICAvLyBub3QgYW5vdGhlciBuZXN0ZWQgcXVhZCBpbmRleCwgdGhyb3cgZXJyb3JcbiAgICAgIGVyciA9IG5ldyBFcnJvcihcIlVuZXhwZWN0ZWQgZGF0YSB0eXBlXCIpO1xuICAgICAgdGhyb3cgZXJyO1xuICAgIH1cblxuICAgIC8vIGNhbGN1bGF0ZSBuZXh0IHF1YWR0cmVlIGRlcHRoIGRhdGFcbiAgICBxdWFkRGF0YS5taWRMYXQgPSAocXVhZERhdGEudG9wICsgcXVhZERhdGEuYm90dG9tKSAvIDI7XG4gICAgcXVhZERhdGEubWlkTG9uID0gKHF1YWREYXRhLmxlZnQgKyBxdWFkRGF0YS5yaWdodCkgLyAyO1xuICB9XG59XG5cbmV4cG9ydCBmdW5jdGlvbiB0b09mZnNldCh0aW1lWm9uZTogc3RyaW5nKSB7XG4gIGNvbnN0IGRhdGUgPSBuZXcgRGF0ZSgpO1xuICBjb25zdCB1dGNEYXRlID0gbmV3IERhdGUoZGF0ZS50b0xvY2FsZVN0cmluZyhcImVuLVVTXCIsIHsgdGltZVpvbmU6IFwiVVRDXCIgfSkpO1xuICBjb25zdCB0ekRhdGUgPSBuZXcgRGF0ZShkYXRlLnRvTG9jYWxlU3RyaW5nKFwiZW4tVVNcIiwgeyB0aW1lWm9uZSB9KSk7XG4gIHJldHVybiAodHpEYXRlLmdldFRpbWUoKSAtIHV0Y0RhdGUuZ2V0VGltZSgpKSAvIDZlNDtcbn1cbiIsInR5cGUgT2NlYW5ab25lID0ge1xuICBsZWZ0OiBudW1iZXJcbiAgcmlnaHQ6IG51bWJlclxuICB0emlkOiBzdHJpbmdcbn1cblxuZXhwb3J0IGNvbnN0IG9jZWFuWm9uZXM6IE9jZWFuWm9uZVtdID0gW1xuICB7IHR6aWQ6ICdFdGMvR01ULTEyJywgbGVmdDogMTcyLjUsIHJpZ2h0OiAxODAgfSxcbiAgeyB0emlkOiAnRXRjL0dNVC0xMScsIGxlZnQ6IDE1Ny41LCByaWdodDogMTcyLjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVC0xMCcsIGxlZnQ6IDE0Mi41LCByaWdodDogMTU3LjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVC05JywgbGVmdDogMTI3LjUsIHJpZ2h0OiAxNDIuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTgnLCBsZWZ0OiAxMTIuNSwgcmlnaHQ6IDEyNy41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQtNycsIGxlZnQ6IDk3LjUsIHJpZ2h0OiAxMTIuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTYnLCBsZWZ0OiA4Mi41LCByaWdodDogOTcuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTUnLCBsZWZ0OiA2Ny41LCByaWdodDogODIuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTQnLCBsZWZ0OiA1Mi41LCByaWdodDogNjcuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTMnLCBsZWZ0OiAzNy41LCByaWdodDogNTIuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTInLCBsZWZ0OiAyMi41LCByaWdodDogMzcuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01ULTEnLCBsZWZ0OiA3LjUsIHJpZ2h0OiAyMi41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQnLCBsZWZ0OiAtNy41LCByaWdodDogNy41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQrMScsIGxlZnQ6IC0yMi41LCByaWdodDogLTcuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01UKzInLCBsZWZ0OiAtMzcuNSwgcmlnaHQ6IC0yMi41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQrMycsIGxlZnQ6IC01Mi41LCByaWdodDogLTM3LjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVCs0JywgbGVmdDogLTY3LjUsIHJpZ2h0OiAtNTIuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01UKzUnLCBsZWZ0OiAtODIuNSwgcmlnaHQ6IC02Ny41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQrNicsIGxlZnQ6IC05Ny41LCByaWdodDogLTgyLjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVCs3JywgbGVmdDogLTExMi41LCByaWdodDogLTk3LjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVCs4JywgbGVmdDogLTEyNy41LCByaWdodDogLTExMi41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQrOScsIGxlZnQ6IC0xNDIuNSwgcmlnaHQ6IC0xMjcuNSB9LFxuICB7IHR6aWQ6ICdFdGMvR01UKzEwJywgbGVmdDogLTE1Ny41LCByaWdodDogLTE0Mi41IH0sXG4gIHsgdHppZDogJ0V0Yy9HTVQrMTEnLCBsZWZ0OiAtMTcyLjUsIHJpZ2h0OiAtMTU3LjUgfSxcbiAgeyB0emlkOiAnRXRjL0dNVCsxMicsIGxlZnQ6IC0xODAsIHJpZ2h0OiAtMTcyLjUgfSxcbl1cblxuLyoqXG4gKiBGaW5kIHRoZSBFdGMvR01UKiB0aW1lem9uZSBuYW1lKHMpIGNvcnJlc3BvbmRpbmcgdG8gdGhlIGdpdmVuIGxvbmdpdHVlLlxuICpcbiAqIEBwYXJhbSBsb24gVGhlIGxvbmdpdHVkZSB0byBhbmFseXplXG4gKiBAcmV0dXJucyBBbiBhcnJheSBvZiBzdHJpbmdzIG9mIFRaSURzXG4gKi9cbmV4cG9ydCBmdW5jdGlvbiBnZXRUaW1lem9uZUF0U2VhKGxvbjogbnVtYmVyKTogc3RyaW5nW10ge1xuICAvLyBjb29yZGluYXRlcyBhbG9uZyB0aGUgMTgwIGxvbmdpdHVkZSBzaG91bGQgcmV0dXJuIHR3byB6b25lc1xuICBpZiAobG9uID09PSAtMTgwIHx8IGxvbiA9PT0gMTgwKSB7XG4gICAgcmV0dXJuIFsnRXRjL0dNVCsxMicsICdFdGMvR01ULTEyJ11cbiAgfVxuICBjb25zdCB0enMgPSBbXVxuICBmb3IgKGxldCBpID0gMDsgaSA8IG9jZWFuWm9uZXMubGVuZ3RoOyBpKyspIHtcbiAgICBjb25zdCB6ID0gb2NlYW5ab25lc1tpXVxuICAgIGlmICh6LmxlZnQgPD0gbG9uICYmIHoucmlnaHQgPj0gbG9uKSB7XG4gICAgICB0enMucHVzaCh6LnR6aWQpXG4gICAgfSBlbHNlIGlmICh6LnJpZ2h0IDwgbG9uKSB7XG4gICAgICBicmVha1xuICAgIH1cbiAgfVxuICByZXR1cm4gdHpzXG59XG4iXX0=
