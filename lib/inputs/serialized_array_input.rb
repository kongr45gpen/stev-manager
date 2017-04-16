class SerializedArrayInput < Formtastic::Inputs::CheckBoxesInput
  def selected_values
    @object.send(method) || []
  end

  def hidden_field_for_all
    ''
  end
end