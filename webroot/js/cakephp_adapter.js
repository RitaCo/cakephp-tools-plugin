DS.RESTAdapter.reopen({
	buildURL: function(type, id) {
		var url = this._super(type,id);
		return url+'.json';	
	}
});

DS.RESTSerializer.reopen({
  normalize: function(type, hash, property) {
    var normalized = {}, normalizedProp;

    for (var prop in hash) {
      if (prop.substr(-3) === '_id') {
        // belongsTo relationships
        normalizedProp = prop.slice(0, -3);
      } else if (prop.substr(-4) === '_ids') {
        // hasMany relationship
        normalizedProp = Ember.String.pluralize(prop.slice(0, -4));
      } else {
        // regualarAttribute
        normalizedProp = prop;
      }

      normalizedProp = Ember.String.camelize(normalizedProp);
      normalized[normalizedProp] = hash[prop];
    }

    return this._super(type, normalized, property);
  }
});
