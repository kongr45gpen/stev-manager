module ActiveAdmin
  module Views
    class IndexAsFinal < ActiveAdmin::Views::IndexAsTable
      def build(page_presenter, collection)
        table_options = {
            id: "index_table_#{active_admin_config.resource_name.plural}",
            sortable: true,
            class: "index_table index",
            i18n: active_admin_config.resource_class,
            paginator: page_presenter[:paginator] != false,
            row_class: page_presenter[:row_class]
        }

        collection = collection.sort_by { |vol| vol.surname }

        table_for collection, table_options do |t|
          table_config_block = page_presenter.block || default_table
          instance_exec(t, &table_config_block)
        end
      end

      def self.index_name
        "final"
      end
    end
  end
end
