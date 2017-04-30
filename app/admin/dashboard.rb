ActiveAdmin.register_page "Dashboard" do

  menu priority: 1, label: proc{ I18n.t("active_admin.dashboard") }

  content title: proc{ I18n.t("active_admin.dashboard") } do
    columns do
      column do
        panel "Recent Logins" do
          ul do
            User.order(current_sign_in_at: "DESC").where.not(last_sign_in_at: nil).limit(5).map do |user|
              li link_to(user.email, admin_user_path(user)) + ' ' + time_ago_in_words(user.current_sign_in_at) + ' ' + I18n.t(:ago)
            end
          end
        end
      end

      column do
        panel "Comments" do
          table_for ActiveAdmin::Comment.last(10) do
            column "Commenter", :author
            column "Resource", :resource
            column "Body", :body
          end
        end
      end

      column do
        panel "Events" do
          ul do
            Event.limit(20).map do |event|
              li link_to(event.title, admin_event_path(event))
            end
          end
        end
      end
    end

    # Here is an example of a simple dashboard with columns and panels.
    #
    # columns do
    #   column do
    #     panel "Recent Posts" do
    #       ul do
    #         Post.recent(5).map do |post|
    #           li link_to(post.title, admin_post_path(post))
    #         end
    #       end
    #     end
    #   end

    #   column do
    #     panel "Info" do
    #       para "Welcome to ActiveAdmin."
    #     end
    #   end
    # end
  end # content
end
