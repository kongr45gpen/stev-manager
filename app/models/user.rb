class User < ApplicationRecord
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable,
  # :registerable, :recoverable, :validatable
  devise :database_authenticatable,
         :rememberable, :trackable

  def to_s
    '#' + id.to_s + ': ' + email
  end
end
