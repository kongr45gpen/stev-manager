require 'test_helper'

class VolunteersControllerTest < ActionDispatch::IntegrationTest
  test "should get index" do
    get volunteers_url
    assert_response :success
  end

end
