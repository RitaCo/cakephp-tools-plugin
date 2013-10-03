DS.RESTAdapter.reopen({
	buildURL: function(type, id) {
		var url = this._super(type,id);
		return url+'.json';	
	}
});

DS.RESTSerializer.reopen({
   keyForRelationship: function(rel, kind) {
      if (kind === 'belongsTo') {
        var underscored = Ember.String.underscore(rel);
        return underscored + '_id';
      } else {
        var singular = Ember.String.singularize(rel);
        return Ember.String.underscore(singular) + '_ids';
      }
    }
});