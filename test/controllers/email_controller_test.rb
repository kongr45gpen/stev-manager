require 'test_helper'

class EmailControllerTest < ActionDispatch::IntegrationTest
  test "should get index" do
    get email_url
    assert_response :success
  end

end
