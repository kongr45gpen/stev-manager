module ActiveAdmin
  module Views
    class IndexAsMedia < ActiveAdmin::Views::IndexAsTable
      def self.index_name
        "media"
      end
    end
  end
end
